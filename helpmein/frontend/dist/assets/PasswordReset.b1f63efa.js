import{i as b,aa as g,av as h,ab as w,R as v,J as y,S as x,_ as k,l as S,x as i,w as m,y as l,o as F,m as s,Q as f}from"./index.7a527825.js";import{c as P,a as B}from"./object.77422023.js";import{S as u}from"./sweetalert2.74fe64d4.js";const E=b({name:"password-reset",components:{Field:g,VForm:h,ErrorMessage:w},setup(){const o=v(),c=x(),t=y(null),d=P().shape({email:B().email().required().label("Email")});return{onSubmitForgotPassword:async e=>{var r,n;e=e,t.value.disabled=!0,(r=t.value)==null||r.setAttribute("data-kt-indicator","on"),await o.forgotPassword(e);const a=o.errors;a?u.fire({text:a[0],icon:"error",buttonsStyling:!1,confirmButtonText:"Try again!",heightAuto:!1,customClass:{confirmButton:"btn fw-semobold btn-light-danger"}}):u.fire({text:"Password reset link successfully sent",icon:"success",buttonsStyling:!1,confirmButtonText:"Ok, got it!",heightAuto:!1,customClass:{confirmButton:"btn fw-semobold btn-light-primary"}}).then(()=>{c.push({name:"sign-in"})}),(n=t.value)==null||n.removeAttribute("data-kt-indicator"),t.value.disabled=!1},forgotPassword:d,submitButton:t}}}),C={class:"w-lg-500px p-10"},V=s("div",{class:"text-center mb-10"},[s("h1",{class:"text-dark mb-3"},"Forgot Password ?"),s("div",{class:"text-gray-400 fw-semobold fs-4"}," Enter your email to reset your password. ")],-1),A={class:"fv-row mb-10"},$=s("label",{class:"form-label fw-bold text-gray-900 fs-6"},"Email",-1),T={class:"fv-plugins-message-container"},M={class:"fv-help-block"},N={class:"d-flex flex-wrap justify-content-center pb-lg-0"},R={type:"submit",ref:"submitButton",id:"kt_password_reset_submit",class:"btn btn-lg btn-primary fw-bold me-4"},j=s("span",{class:"indicator-label"}," Submit ",-1),q=s("span",{class:"indicator-progress"},[f(" Please wait... "),s("span",{class:"spinner-border spinner-border-sm align-middle ms-2"})],-1),J=[j,q];function O(o,c,t,d,p,e){const a=l("Field"),r=l("ErrorMessage"),n=l("router-link"),_=l("VForm");return F(),S("div",C,[i(_,{class:"form w-100 fv-plugins-bootstrap5 fv-plugins-framework",onSubmit:o.onSubmitForgotPassword,id:"kt_login_password_reset_form","validation-schema":o.forgotPassword},{default:m(()=>[V,s("div",A,[$,i(a,{class:"form-control form-control-solid",type:"email",placeholder:"",name:"email",autocomplete:"off"}),s("div",T,[s("div",M,[i(r,{name:"email"})])])]),s("div",N,[s("button",R,J,512),i(n,{to:"/sign-up",class:"btn btn-lg btn-light-primary fw-bold"},{default:m(()=>[f("Cancel")]),_:1})])]),_:1},8,["onSubmit","validation-schema"])])}const G=k(E,[["render",O]]);export{G as default};
