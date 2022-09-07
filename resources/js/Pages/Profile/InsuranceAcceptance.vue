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
                  <h3 class="h3_tittle">What insurances do you accept?</h3>
                  <div v-if="success" class="alert alert-success text-dark font-weight-bold mt-4">
                     {{ success }}
                  </div>
                  <div v-if="error" class="alert alert-warning text-dark font-weight-bold mt-4">
                     {{ error }}
                  </div>
                  <div class="insurances ">
                     <div class="inputs_telemedicine insurances_inputs">
                       
                           <div class="check_inputs_all">
                              <label>
                                 <input type="checkbox" value="Blue Cross Blue Shield" v-model="form.insurances">Blue Cross Blue Shield
                              </label>
                              <label>
                                 <input type="checkbox" value="United Healthcare" v-model="form.insurances">United Healthcare
                              </label>
                              <label>
                                 <input type="checkbox" value="HealthPartners" v-model="form.insurances">HealthPartners
                              </label>
                              <label>
                                 <input type="checkbox" value="Kaiser Foundation" v-model="form.insurances">Kaiser Foundation
                              </label>
                              <label>
                                 <input type="checkbox" value="Anthem, Inc." v-model="form.insurances">Anthem, Inc.
                              </label>
                              <label>
                                 <input type="checkbox" value="Centene Corp" v-model="form.insurances">Centene Corp
                              </label>
                              <label>
                                 <input type="checkbox" value="Humana" v-model="form.insurances">Humana
                              </label>                           
                           </div>
                           <div class="check_inputs_all">                           
                              <label>
                                 <input type="checkbox" value="Molina Healthcare, Inc." v-model="form.insurances">Molina Healthcare, Inc.
                              </label>
                              <label>
                                 <input type="checkbox" value="CVS" v-model="form.insurances">CVS
                              </label>
                              <label>
                                 <input type="checkbox" value="Independence Health Group" v-model="form.insurances">Independence Health Group
                              </label>
                              <label>
                                 <input type="checkbox" value="HCSC" v-model="form.insurances">HCSC
                              </label>
                              <label>
                                 <input type="checkbox" value="Aetna" v-model="form.insurances">Aetna
                              </label>
                              <label>
                                 <input type="checkbox" value="Cigna Health" v-model="form.insurances">Cigna Health
                              </label>
                              <label>
                                 <input type="checkbox" value="Other" v-model="form.insurances" @change="insuranceHandler($event)">Other
                              </label>
                              <div class="insurances_rep" v-if="others">
                                 <div  v-for="(doc,index) in form.other_insurance" :key="index" class="other_in">
                                    <input type="text" v-model="form.other_insurance[index]"/>
                                    <span v-if="index > 0 " @click="removeInsurance(index)">X</span>
                                    <span v-else @click="repeatInsurance($event)">+</span>
                                 </div>
                              </div>
                           </div>                        
                     </div>
                  </div>
                  <div class="btn_flex update_btn ">
                     <button class="btn_frst" @click="back()">Back</button>
                     <button :disabled="loading" @click="submitHandle()">Continue
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
        props: ['insurance', 'other_insurance'],
        data() {
            return { 
               insurances:[{
                  "key": ""
               }],
               success: "",             
               error: "",                
               loading: false,  
               others: (this.insurance.includes("Other"))? true:false,
               form: { 
                  insurances: this.insurance,
                  other_insurance: (this.other_insurance)? this.other_insurance: ['']                                                         
               },
            }
        },
        methods: {     
            removeInsurance(index){
               this.form.other_insurance.splice(index, index);  
            },
            repeatInsurance(e){
               this.form.other_insurance.push("");
            },     
            insuranceHandler(e){
               this.others = e.target.checked;
               console.log(e.target.checked, "dfssfsdfsd");
            },
            back(){
                window.location.href = '/offer-telemedicine';
            },
            submitHandle(){
               console.log(this.form);
               this.error = "";
               this.success = "";     
               this.loading = true; 
               //console.log(this.insurances, this.form, "this.form"); return false;
               axios.post('/insurance-acceptance', this.form)
                    .then(response => {
                        //console.log(this.insurances, this.form, "this.form"); return false;
                        if(response.data.status) {
                           this.success = response.data.message
                           setTimeout(() => {
                            this.$inertia.visit('/appointment-fee');
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
            if(this.form.other_insurance.length < 1){
               this.form.other_insurance.push("");
            }
        }
    }
</script>