<template>
    <Head title="Bio" />
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
                        <h3 class="h3_tittle">Bio</h3>
                        <div v-if="success" class="alert alert-success text-dark font-weight-bold mt-4">
                           {{ success }}
                        </div>
                        <div v-if="error" class="alert alert-warning text-dark font-weight-bold mt-4">
                           {{ error }}
                        </div>
                        <textarea placeholder="Your Bio" v-model="form.bio" name="bio" required></textarea>
                        <div class="btn_flex update_btn ">
                            <!-- <button class="btn_frst"></button> -->
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
        props: ['bio'],
        data() {
            return { 
                success: "",             
                error: "",                
                loading: false,  
                form: { 
                    bio: this.bio,                                                            
                },
            }
        },
        methods: {    
            back(){
                window.location.href = history.back();
            },        
            submit: function (data) {
               this.error = "";
               this.success = "";     
               this.loading = true;           
               axios.post('/profile-bio', this.form)
                    .then(response => {
                        if(response.data.status) {
                           this.success = response.data.message
                           setTimeout(() => {
                            this.$inertia.visit('/areas-specialization');
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