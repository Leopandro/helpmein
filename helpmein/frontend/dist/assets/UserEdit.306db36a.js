import{_,ap as p,l as i,m as e,an as b,n as r,q as d,K as c,L as m,o as n,Q as h}from"./index.7a527825.js";import{S as g}from"./sweetalert2.74fe64d4.js";const v={name:"UserEdit",data(){return{errors:{},model:{email:"",avatar:"",name:"",surname:""}}},mounted(){p.get("user/info/"+this.$route.params.id).then(a=>{console.log(a),this.model=a.data.data})},methods:{submitForm(){p.post("user/edit/"+this.$route.params.id,this.model).then(()=>{g.fire({text:"\u041A\u043B\u0438\u0435\u043D\u0442 \u0443\u0441\u043F\u0435\u0448\u043D\u043E \u0438\u0437\u043C\u0435\u043D\u0435\u043D",icon:"success",buttonsStyling:!1,confirmButtonText:"\u041E\u043A!",heightAuto:!1,customClass:{confirmButton:"btn fw-semobold btn-light-primary"}}).then(()=>{this.$router.push({name:"user-list"})})}).catch(({response:a})=>{console.log(a),this.errors=a.data.errors,g.fire({text:a.data.message,icon:"error",buttonsStyling:!1,confirmButtonText:"Try again!",heightAuto:!1,customClass:{confirmButton:"btn fw-semobold btn-light-danger"}})})},deleteImage(){console.log("delete"),this.model.avatar=""},handleImage(a){console.log("handleImage");const t=a.target.files[0];this.createBase64Image(t)},createBase64Image(a){const t=new FileReader;t.onload=u=>{this.model.avatar="url("+u.target.result+")"},t.readAsDataURL(a)}}},f={class:"card"},y=e("div",{class:"card-header"},[e("div",{class:"card-title"}," \u0420\u0435\u0434\u0430\u043A\u0442\u0438\u0440\u043E\u0432\u0430\u0442\u044C \u043A\u043B\u0438\u0435\u043D\u0442\u0430 ")],-1),w={class:"card-body"},k={class:"row mb-6"},x=e("label",{class:"col-lg-4 col-form-label fw-semobold fs-6"},"\u0410\u0432\u0430\u0442\u0430\u0440",-1),B={class:"col-lg-8"},I={class:"image-input image-input-outline","data-kt-image-input":"true",style:{"background-image":'url("/metronic8/vue/demo1//media/avatars/blank.png")'}},E={class:"btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow","data-kt-image-input-action":"change","data-bs-toggle":"tooltip",title:"Change avatar"},C=e("i",{class:"bi bi-pencil-fill fs-7"},null,-1),S=e("input",{type:"hidden",name:"avatar_remove"},null,-1),U=e("i",{class:"bi bi-x fs-2"},null,-1),V=[U],j=e("div",{class:"form-text"},"Allowed file types: png, jpg, jpeg.",-1),A={key:0,class:"fv-plugins-message-container invalid-feedback"},T={"data-field":"daterangepicker_input","data-validator":"notEmpty"},q={class:"row mb-6"},F=e("label",{class:"col-lg-4 col-form-label required fw-semobold fs-6"},"E-mail",-1),D={class:"col-lg-8 fv-row"},N={key:0,class:"fv-plugins-message-container invalid-feedback"},R={"data-field":"daterangepicker_input","data-validator":"notEmpty"},L={class:"row mb-6"},z=e("label",{class:"col-lg-4 col-form-label required fw-semobold fs-6"},"\u0418\u043C\u044F",-1),K={class:"col-lg-8 fv-row"},M={key:0,class:"fv-plugins-message-container invalid-feedback"},P={"data-field":"daterangepicker_input","data-validator":"notEmpty"},Q={class:"row mb-6"},G=e("label",{class:"col-lg-4 col-form-label required fw-semobold fs-6"},"\u0424\u0430\u043C\u0438\u043B\u0438\u044F",-1),H={class:"col-lg-8 fv-row"},J={key:0,class:"fv-plugins-message-container invalid-feedback"},O={"data-field":"daterangepicker_input","data-validator":"notEmpty"},W=e("span",{class:"indicator-label"}," \u0421\u043E\u0445\u0440\u0430\u043D\u0438\u0442\u044C ",-1),X=e("span",{class:"indicator-progress"},[h(" Please wait... "),e("span",{class:"spinner-border spinner-border-sm align-middle ms-2"})],-1),Y=[W,X];function Z(a,t,u,$,s,l){return n(),i("div",f,[y,e("div",w,[e("div",k,[x,e("div",B,[e("div",I,[e("div",{class:"image-input-wrapper w-125px h-125px",style:b({"background-image":s.model.avatar?s.model.avatar:'url("/media/avatars/blank.png")'})},null,4),e("label",E,[C,e("input",{type:"file",onChange:t[0]||(t[0]=(...o)=>l.handleImage&&l.handleImage(...o)),name:"avatar",accept:".png, .jpg, .jpeg"},null,32),S]),e("span",{onClick:t[1]||(t[1]=(...o)=>l.deleteImage&&l.deleteImage(...o)),class:"btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow","data-kt-image-input-action":"remove","data-bs-toggle":"tooltip",title:"Remove avatar"},V)]),j,s.errors.avatar?(n(),i("div",A,[e("div",T,r(s.errors.avatar[0]),1)])):d("",!0)])]),e("div",q,[F,e("div",D,[c(e("input",{"onUpdate:modelValue":t[2]||(t[2]=o=>s.model.email=o),type:"text",class:"form-control",placeholder:"name@example.com"},null,512),[[m,s.model.email]]),s.errors.email?(n(),i("div",N,[e("div",R,r(s.errors.email[0]),1)])):d("",!0)])]),e("div",L,[z,e("div",K,[c(e("input",{"onUpdate:modelValue":t[3]||(t[3]=o=>s.model.name=o),type:"text",class:"form-control",placeholder:""},null,512),[[m,s.model.name]]),s.errors.name?(n(),i("div",M,[e("div",P,r(s.errors.name[0]),1)])):d("",!0)])]),e("div",Q,[G,e("div",H,[c(e("input",{"onUpdate:modelValue":t[4]||(t[4]=o=>s.model.surname=o),type:"text",class:"form-control",placeholder:""},null,512),[[m,s.model.surname]]),s.errors.surname?(n(),i("div",J,[e("div",O,r(s.errors.surname[0]),1)])):d("",!0)])]),e("button",{ref:"submitButton",href:"javascript:;",onClick:t[5]||(t[5]=(...o)=>l.submitForm&&l.submitForm(...o)),type:"submit",class:"btn btn-success"},Y,512)])])}const se=_(v,[["render",Z]]);export{se as default};
