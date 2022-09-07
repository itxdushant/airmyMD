<template>
   <Head title="Tags Apply" />
   <div class="section_profile">
         <div class="left_img_section">
            <div class="brand_logo">
               <a href="#"><img src="images/logo.png"></a>
            </div>
         </div>
         <div class="right_detail_section">
            <div class="skip_div">
               <!-- <button>Skip this for now</button> -->
            </div>
            <div class="profile_details_inputs">
               <div class="account_information_form">
                  <form @submit.prevent="submit">
                     <h3 class="h3_tittle">Select the tags that apply to you and many of your patients</h3>
                     <div v-if="success" class="alert alert-success text-dark font-weight-bold mt-4">
                        {{ success }}
                     </div>
                     <div v-if="error" class="alert alert-warning text-dark font-weight-bold mt-4">
                        {{ error }}
                     </div>
                     <div class="patients">
                        <div class="btn_inputs_select">
                           <div class="label">
                              <input type="checkbox" id="african-american" value="African American" v-model="form.tags">
                              <label for="african-american">African American
                              </label>
                           </div>
                           <div class="label">
                              <input type="checkbox" id="arthritis" value="Arthritis" v-model="form.tags">
                              <label for="arthritis"> Arthritis    
                              </label>
                           </div>
                           <div class="label">
                              <input type="checkbox" id="age-18-24" value="Age-18-24" v-model="form.tags">
                              <label for="age-18-24">Age-18-24
                              </label>
                           </div>
                        </div>
                        <div class="btn_inputs_select">
                           <div class="label">
                              <input type="checkbox" id="age-25-40" value="Age-25-40" v-model="form.tags">
                              <label for="age-25-40">Age-25-40
                              </label>
                           </div>
                           <div class="label">
                              <input type="checkbox" id="age-40-60" value="Age-40-60" v-model="form.tags">
                              <label for="age-40-60">Age-40-60   
                              </label>  
                           </div>
                           <div class="label">
                              <input type="checkbox" id="age-60" value="Age-60+" v-model="form.tags">
                              <label for="age-60">Age-60+
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="btn_flex update_btn ">
                        <button class="btn_frst" @click="back()">Back</button>
                        <button :disabled="loading">Continue
                           <div class="spinner-border spinner-border-sm ml-5" v-if="loading" role="status">
                            <!-- <span class="sr-only">Loading...</span> -->
                           </div>
                        </button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
</template>
<script>
    // import SideBar from '@/Components/SideBar'
    export default {
        components: {},
        props: ['selectedtags'],
        data() {
            return { 
                success: "",             
                error: "",                
                loading: false,  
                form: { 
                    tags: [],                                                            
                },
            }
        },
        methods: {       
            //insurance-acceptance
            back(){
                window.location.href = '/insurance-acceptance';
            },     
            submit: function (data) {
               this.error = "";
               this.success = "";     
               this.loading = true;   
               axios.post('/tags-apply', this.form)
                    .then(response => {
                        if(response.data.status) {
                           this.success = response.data.message
                           setTimeout(() => {
                              setTimeout(() => {
                                 this.$inertia.visit('/appointment-fee');
                              }, 1000)
                           }, 1000)
                        } else {
                           //this.error = "Please try again"
                           this.error = response.data.message;
                        }
                    })
                    .catch(error => {
                        this.error = "Please try again";
                        console.log(error);
                    })
                    .then(() => {
                        this.loading = false;
                    })              
            },          
        },
        mounted() { 
        }
    }
</script>