import{_ as g,ar as b,l as i,m as e,ap as _,n,q as d,K as c,L as m,o as r,Q as f}from"./index.5e2136db.js";import{S as p}from"./sweetalert2.all.4f02a328.js";const h={name:"UserCreate",data(){return{errors:{},model:{email:"",avatar:"",name:"",surname:""}}},methods:{async submitForm(){this.$refs.submitButton.disabled=!0,this.$refs.submitButton.setAttribute("data-kt-indicator","on"),console.log(this.$refs.submitButton),await b.post("user/create",this.model).then(()=>{p.fire({text:"\u041A\u043B\u0438\u0435\u043D\u0442 \u0443\u0441\u043F\u0435\u0448\u043D\u043E \u0441\u043E\u0437\u0434\u0430\u043D",icon:"success",buttonsStyling:!1,confirmButtonText:"Ok, got it!",heightAuto:!1,customClass:{confirmButton:"btn fw-semobold btn-light-primary"}}).then(()=>{this.$router.push({name:"user-list"})})}).catch(({response:o})=>{console.log(o),this.errors=o.data.errors,p.fire({text:o.data.message,icon:"error",buttonsStyling:!1,confirmButtonText:"Try again!",heightAuto:!1,customClass:{confirmButton:"btn fw-semobold btn-light-danger"}})}),this.$refs.submitButton.disabled=!1,this.$refs.submitButton.setAttribute("data-kt-indicator","off")},deleteImage(){console.log("delete"),this.model.avatar=""},handleImage(o){console.log("handleImage");const t=o.target.files[0];this.createBase64Image(t)},createBase64Image(o){const t=new FileReader;t.onload=u=>{this.model.avatar="url("+u.target.result+")"},t.readAsDataURL(o)}}},v={class:"card"},y=e("div",{class:"card-header"},[e("div",{class:"card-title"}," \u0414\u043E\u0431\u0430\u0432\u0438\u0442\u044C \u043A\u043B\u0438\u0435\u043D\u0442\u0430 ")],-1),k={class:"card-body"},w={class:"row mb-6"},x=e("label",{class:"col-lg-4 col-form-label fw-semobold fs-6"},"\u0410\u0432\u0430\u0442\u0430\u0440",-1),B={class:"col-lg-8"},I={class:"image-input image-input-outline","data-kt-image-input":"true",style:{"background-image":'url("/metronic8/vue/demo1//media/avatars/blank.png")'}},C={class:"btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow","data-kt-image-input-action":"change","data-bs-toggle":"tooltip",title:"Change avatar"},A=e("i",{class:"bi bi-pencil-fill fs-7"},null,-1),S=e("input",{type:"hidden",name:"avatar_remove"},null,-1),E=e("i",{class:"bi bi-x fs-2"},null,-1),U=[E],V=e("div",{class:"form-text"},"Allowed file types: png, jpg, jpeg.",-1),j={key:0,class:"fv-plugins-message-container invalid-feedback"},T={"data-field":"daterangepicker_input","data-validator":"notEmpty"},q={class:"row mb-6"},F=e("label",{class:"col-lg-4 col-form-label required fw-semobold fs-6"},"E-mail",-1),D={class:"col-lg-8 fv-row"},N={key:0,class:"fv-plugins-message-container invalid-feedback"},R={"data-field":"daterangepicker_input","data-validator":"notEmpty"},L={class:"row mb-6"},z=e("label",{class:"col-lg-4 col-form-label required fw-semobold fs-6"},"\u0418\u043C\u044F",-1),K={class:"col-lg-8 fv-row"},M={key:0,class:"fv-plugins-message-container invalid-feedback"},O={"data-field":"daterangepicker_input","data-validator":"notEmpty"},P={class:"row mb-6"},Q=e("label",{class:"col-lg-4 col-form-label required fw-semobold fs-6"},"\u0424\u0430\u043C\u0438\u043B\u0438\u044F",-1),G={class:"col-lg-8 fv-row"},H={key:0,class:"fv-plugins-message-container invalid-feedback"},J={"data-field":"daterangepicker_input","data-validator":"notEmpty"},W=e("span",{class:"indicator-label"}," \u0421\u043E\u0445\u0440\u0430\u043D\u0438\u0442\u044C ",-1),X=e("span",{class:"indicator-progress"},[f(" Please wait... "),e("span",{class:"spinner-border spinner-border-sm align-middle ms-2"})],-1),Y=[W,X];function Z(o,t,u,$,s,l){return r(),i("div",v,[y,e("div",k,[e("div",w,[x,e("div",B,[e("div",I,[e("div",{class:"image-input-wrapper w-125px h-125px",style:_({"background-image":s.model.avatar?s.model.avatar:'url("/media/avatars/blank.png")'})},null,4),e("label",C,[A,e("input",{type:"file",onChange:t[0]||(t[0]=(...a)=>l.handleImage&&l.handleImage(...a)),name:"avatar",accept:".png, .jpg, .jpeg"},null,32),S]),e("span",{onClick:t[1]||(t[1]=(...a)=>l.deleteImage&&l.deleteImage(...a)),class:"btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow","data-kt-image-input-action":"remove","data-bs-toggle":"tooltip",title:"Remove avatar"},U)]),V,s.errors.avatar?(r(),i("div",j,[e("div",T,n(s.errors.avatar[0]),1)])):d("",!0)])]),e("div",q,[F,e("div",D,[c(e("input",{"onUpdate:modelValue":t[2]||(t[2]=a=>s.model.email=a),type:"text",class:"form-control",placeholder:"name@example.com"},null,512),[[m,s.model.email]]),s.errors.email?(r(),i("div",N,[e("div",R,n(s.errors.email[0]),1)])):d("",!0)])]),e("div",L,[z,e("div",K,[c(e("input",{"onUpdate:modelValue":t[3]||(t[3]=a=>s.model.name=a),type:"text",class:"form-control",placeholder:""},null,512),[[m,s.model.name]]),s.errors.name?(r(),i("div",M,[e("div",O,n(s.errors.name[0]),1)])):d("",!0)])]),e("div",P,[Q,e("div",G,[c(e("input",{"onUpdate:modelValue":t[4]||(t[4]=a=>s.model.surname=a),type:"text",class:"form-control",placeholder:""},null,512),[[m,s.model.surname]]),s.errors.surname?(r(),i("div",H,[e("div",J,n(s.errors.surname[0]),1)])):d("",!0)])]),e("button",{ref:"submitButton",href:"javascript:;",onClick:t[5]||(t[5]=(...a)=>l.submitForm&&l.submitForm(...a)),type:"submit",class:"btn btn-success"},Y,512)])])}const se=g(h,[["render",Z]]);export{se as default};
