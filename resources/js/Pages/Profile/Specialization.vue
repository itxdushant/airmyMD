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
                        <h3 class="h3_tittle">Select all areas that you specialize in</h3>
                        <div v-if="success" class="alert alert-success text-dark font-weight-bold mt-4">
                           {{ success }}
                        </div>
                        <div v-if="error" class="alert alert-warning text-dark font-weight-bold mt-4">
                           {{ error }}
                        </div>
                        <div class="specialization">
                            <div class="specialization_card">
                                <div class="box_card" v-for="specialization in specializations">
                                    <label>
                                        <input type="checkbox" name="product" class="card-input-element" :value="specialization.name" @click="checkbox($event)" v-model="form.specialize">
                                        <div class="panel panel-default card-input">
                                            <img :src="specialization.icon">
                                            <h5>{{specialization.name}}</h5>
                                        </div>
                                    </label>
                                </div>
                                <!-- <div class="box_card">
                                    <label>
                                        <input type="checkbox" name="product" class="card-input-element" value="Dentist" v-model="form.specialize">
                                        <div class="panel panel-default card-input">
                                            <img src="images/dentist.png">
                                            <h5>Dentist</h5>
                                        </div>
                                    </label>
                                </div>
                                <div class="box_card">
                                    <label>
                                        <input type="checkbox" name="product" class="card-input-element" value="Psychiatrists" v-model="form.specialize">
                                        <div class="panel panel-default card-input">
                                            <img src="images/Psychiatrists.png">
                                            <h5>Psychiatrists</h5>
                                        </div>
                                    </label>
                                </div>
                                <div class="box_card">
                                    <label>
                                        <input type="checkbox" name="product" class="card-input-element" value="Traumatology" v-model="form.specialize">
                                        <div class="panel panel-default card-input">
                                            <img src="images/Traumatology.png">
                                            <h5>Traumatology</h5>
                                        </div>
                                    </label>
                                </div> -->
                            </div>
                            <!-- <div class="specialization_card">
                                <div class="box_card">
                                    <label>
                                        <input type="checkbox" name="product" class="card-input-element" value="Dermatologists" v-model="form.specialize">
                                        <div class="panel panel-default card-input">
                                            <img src="images/Dermatologists.png">
                                            <h5>Dermatologists</h5>
                                        </div>
                                    </label>
                                </div>
                                <div class="box_card">
                                    <label>
                                        <input type="checkbox" name="product" class="card-input-element" value="Internists" v-model="form.specialize">
                                        <div class="panel panel-default card-input">
                                            <img src="images/Internists.png">
                                            <h5>Internists</h5>
                                        </div>
                                    </label>
                                </div>
                                <div class="box_card">
                                    <label>
                                        <input type="checkbox" name="product" class="card-input-element" value="Osteopaths" v-model="form.specialize">
                                        <div class="panel panel-default card-input">
                                            <img src="images/Osteopaths.png">
                                            <h5>Osteopaths</h5>
                                        </div>
                                    </label>
                                </div>
                                <div class="box_card">
                                    <label>
                                        <input type="checkbox" name="product" class="card-input-element" value="Podiatrists" v-model="form.specialize">
                                        <div class="panel panel-default card-input">
                                            <img src="images/Podiatrists.png">
                                            <h5>Podiatrists</h5>
                                        </div>
                                    </label>
                                </div>
                            </div> -->
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
        props: ['specializations', 'selectedSpecialization'],
        data() {
            return { 
                success: "",             
                error: "",                
                loading: false,  
                form: { 
                    specialize: (this.selectedSpecialization)? this.selectedSpecialization: [], 
                },
            }
        },
        methods: { 
            back(){
                window.location.href = '/profile-bio';
            }, 
            checkbox: function(event) {
                console.log(event.target.value)
            },  
            submitHandle(){
               this.error = "";
               this.success = "";     
               this.loading = true;   
               axios.post('/areas-specialization', this.form)
                    .then(response => {
                        if(response.data.status) {
                           this.success = response.data.message
                           setTimeout(() => {
                            this.$inertia.visit('/offer-telemedicine');
                           }, 1000)
                        } else {
                            console.log(response.data.message, "having issues");
                           this.error = response.data.message;
                        }
                    })
                    .catch(error => {
                        this.error = "Please try again";
                        //console.log(error, "dsfsdfds");
                    })
                    .then(() => {
                        this.loading = false;
                    })              
            },          
        },
        mounted() { 
            console.log(this.specializations);
            console.log(this.selectedSpecialization);
        }
    }
</script>