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
                        <h3 class="h3_tittle">Do you currently offer telemedicine?</h3>
                        <div v-if="success" class="alert alert-success text-dark font-weight-bold mt-4">
                            {{ success }}
                        </div>
                        <div v-if="error" class="alert alert-warning text-dark font-weight-bold mt-4">
                            {{ error }}
                        </div>
                        <div class="telemedicine">
                            <div class="inputs_telemedicine">
                                <label class="frst_lebal">
                                    <input type="radio" value="Yes" v-model="form.telemedicine" >Yes
                                </label>
                                <label>
                                    <input type="radio" value="No, and not interested in offering" v-model="form.telemedicine" >No, and not interested in offering
                                </label>
                            </div>
                            <div class="inputs_telemedicine">
                                <label>
                                    <input type="radio" value="No, but interested in offering" v-model="form.telemedicine" >No, but interested in offering
                                </label>
                            </div>
                        </div>
                        <div class="btn_flex update_btn ">
                            <button class="btn_frst" @click="back()">Back</button>
                            <button :disabled="loading"  @click="submitHandle()">Continue
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
        props: ['telemedicine'],
        data() {
            return { 
               success: "",             
               error: "",                
               loading: false,  
               form: { 
                  telemedicine: (this.telemedicine)? this.telemedicine:"",                                                            
               },
            }
        },
        methods: { 
            back(){
                window.location.href = '/areas-specialization';
            },
            submitHandle: function (data) {
               this.error = "";
               this.success = "";     
               this.loading = true;    
               axios.post('/offer-telemedicine', this.form)
                    .then(response => {
                        if(response.data.status) {
                           this.success = response.data.message
                           setTimeout(() => {
                            this.$inertia.visit('/insurance-acceptance');
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
        }
    }
</script>