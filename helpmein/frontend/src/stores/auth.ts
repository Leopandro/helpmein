import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";

export interface User {
  name: string;
  surname: string;
  email: string;
  password: string;
  token: string;
}

export const useAuthStore = defineStore("auth", () => {
  const errors = ref("");
  const messages = ref("");
  const user = ref<User>({} as User);
  const isAuthenticated = ref(!!JwtService.getToken());

  function setAuth(authUser: User) {
    isAuthenticated.value = true;
    console.log(authUser);
    user.value = authUser;
    errors.value = "";
    JwtService.saveToken(user.value.token);
  }

  function setError(error: any) {
      error ? errors.value = error : errors.value = '';
  }
  function setMessage(message: any) {
    messages.value = message;
  }

  function purgeAuth() {
    isAuthenticated.value = false;
    user.value = {} as User;
    errors.value = "";
    JwtService.destroyToken();
  }

  function login(credentials: User) {
    return ApiService.post("auth/login", credentials)
      .then(({ data }) => {
        setAuth(data);
      })
      .catch(({ response }) => {
        setError(response.data.message);
      });
  }

  function logout() {
    purgeAuth();
  }

  function register(credentials: User) {
    return ApiService.post("auth/register", credentials)
      .then(({ data }) => {
        setAuth(data.data);
      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  function updatePassword(object) {
      return ApiService.post("auth/update-password", object)
          .then(() => {

          })
          .catch(({ response }) => {
              setMessage(response.data.message);
              setError(response.data.errors);
          });
  }

  function forgotPassword(email: string) {
    return ApiService.post("auth/remind-password", email)
      .then(() => {

      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  function verifyAuth() {
    if (JwtService.getToken()) {
      ApiService.setHeader();
      ApiService.post("verify_token")
        .then(({ data }) => {
            setAuth(data.data);
        })
        .catch(({ response }) => {
          setError(response.data.errors);
          purgeAuth();
        });
    } else {
      purgeAuth();
    }
  }

  return {
    errors,
      messages,
    user,
    isAuthenticated,
    login,
    logout,
    register,
    updatePassword,
    forgotPassword,
    verifyAuth,
  };
});
