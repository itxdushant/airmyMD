<template>
   <Head title="Specialize" />
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
                  <h3 class="h3_tittle">What is the percentage of your patient gender?</h3>
                  <div v-if="success" class="alert alert-success text-dark font-weight-bold mt-4">
                     {{ success }}
                  </div>
                  <div v-if="error" class="alert alert-warning text-dark font-weight-bold mt-4">
                     {{ error }}
                  </div>
                  <div class="">
                     <div class="selector_percentage row">
                        <div class="col-md-4">
                           <select name="male" id="male" v-model="form.male">
                              <option value="">Male</option>
                              <option value="10">10%</option>
                              <option value="20">20%</option>
                              <option value="30">30%</option>
                           </select>
                        </div>
                        <div class="col-md-4">
                           <select name="female" id="female" v-model="form.female">
                              <option value="">Female</option>
                              <option value="10">10%</option>
                              <option value="20">20%</option>
                              <option value="30">30%</option>
                           </select>
                        </div>
                        <div class="col-md-4">
                           <select name="other" id="other" v-model="form.other">
                              <option value="">Other</option>
                              <option value="10">10%</option>
                              <option value="20">20%</option>
                              <option value="30">30%</option>
                           </select>
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
        props: ['patients_gender'],
        data() {
            return { 
               success: "",             
               error: "",                
               loading: false,  
               form: { 
                  male: "",                                                            
                  female: "",                                                            
                  other: "",                                                            
               },
            }
        },
        methods: {  
            back(){
                  window.location.href = '/areas-specialization';
            },           
            submit: function (data) {
               this.error = "";
               this.success = "";     
               this.loading = true;    
               axios.post('/patients-gender', this.form)
                    .then(response => {
                        if(response.data.status) {
                           this.success = response.data.message
                           setTimeout(() => {
                            this.$inertia.visit('/offer-telemedicine');
                           }, 1000)
                        } else {
                           this.error = "Please try again"
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
            this.form.male = (this.patients_gender.male)?this.patients_gender.male:"";
            this.form.female = (this.patients_gender.female)?this.patients_gender.female:"";
            this.form.other = (this.patients_gender.other)?this.patients_gender.other:"";
        }
    }
</script>