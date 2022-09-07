<template>
<Head title="Profile" />
    <div class="main_body">
        <SideBar/>
        <!-- left_bar end here -->
        <div class="right_section">
            <div class="mobile_header d-block d-lg-none">
                <div class="mobile_flex">
                    <a href="#"><img src="images/logo.png"></a>
                    <div class="toggle_icon">
                        <img src="images/toggle.png">
                    </div>
                </div>
            </div>
            <div class="login_right_section">
                <div class="top_bar_c">
                    <h3 class="h3_tittle">Account</h3>
                    <div class="right_top_input">
                        <!-- <label for="search">
                            <input type="search" name="search">
                            <img src="images/search_1.png">
                        </label> -->
                        <UserBar/>
                    </div>
                </div>
                <div class="account_information_form">
                    <h4 class="h4_tittle">Personal Information</h4>
                    <div v-if="success" class="alert alert-success text-dark font-weight-bold mt-4">
                        {{ success }}
                    </div>
                    <div v-if="error" class="alert alert-warning text-dark font-weight-bold mt-4">
                        {{ error }}
                    </div>
                    <form  @submit.prevent="accountSubmit">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" placeholder="First Name" name="first_name" v-model="form.first_name">
                            </div>
                            <div class="col-md-6">
                                <input type="text" placeholder="Last Name" name="last_name" v-model="form.last_name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="email" placeholder="Email" name="last_name" v-model="user.email">
                            </div>
                            <div class="col-md-6">
                                <input type="text" placeholder="Address" name="address" v-model="form.address" ref="origin" required autocomplete="off">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" placeholder="City" name="city" v-model="form.city">
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="State" name="state" v-model="form.state" />
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="Zip Code" name="zipcode" v-model="form.zipcode" >
                            </div>
                        </div>
                        <div class="btn_update update_btn">
                            <button :disabled="loading">Update
                            <div class="spinner-border spinner-border-sm ml-5" v-if="loading" role="status">
                                <!-- <span class="sr-only">Loading...</span> -->
                            </div>
                            </button>
                        </div>
                    </form>
                    <form @submit.prevent="passwordSubmit">
                        <h4 class="h4_tittle">Change Password</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="password" placeholder="Current Password" required name="c_password" v-model="c_password">
                            </div>
                            <div class="col-md-6">
                                <input type="password" placeholder="New Password" required name="n_password" v-model="n_password">
                            </div>
                        </div>
                        <div class="btn_update update_btn">
                            <div class="btn_update update_btn">
                                <button :disabled="loading2">Update
                                <div class="spinner-border spinner-border-sm ml-5" v-if="loading2" role="status">
                                    <!-- <span class="sr-only">Loading...</span> -->
                                </div>
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- <form @submit.prevent="bankSubmission">
                        <h4 class="h4_tittle">Banking Information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" placeholder="Name on Account" name="account_name">
                            </div>
                            <div class="col-md-6">
                                <input type="text" placeholder="Routing Number" Account Number name="routing_name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <input type="number" placeholder="Account Number" name="account_number">
                            </div>
                        </div>
                        <div class="btn_update update_btn">
                            <div class="btn_update update_btn">
                                <button :disabled="loading">Update
                                <div class="spinner-border spinner-border-sm ml-5" v-if="loading" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                </button>
                            </div>
                        </div>
                    </form> -->

                    <div class="btn_update update_btn" v-if="!bankDetails">
                        <div class="btn_update update_btn">
                            <button :disabled="loading3" @click="connectStripeAccount">Connect Stripe Account
                            <div class="spinner-border spinner-border-sm ml-5" v-if="loading3" role="status">
                                <!-- <span class="sr-only">Loading...</span> -->
                            </div>
                            </button>
                        </div>
                    </div>
                    <div class="" v-else>
                        <!-- You are already connected with stripe <strong>{{bank.account_id}}</strong> This is your account id. -->
                        <form @submit.prevent="bankSubmission">
                            <h4 class="h4_tittle">Banking Information</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" placeholder="Name on Account" name="account_name" v-model="bank.account_holder_name">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" placeholder="Routing Number" v-model="bank.routing_number" name="routing_name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <input type="text" placeholder="Account Number" v-model="bank.account_number" name="account_number">
                                </div>
                            </div>
                            <div class="btn_update update_btn">
                                <div class="btn_update update_btn">
                                    <button :disabled="loading4">Update
                                    <div class="spinner-border spinner-border-sm ml-5" v-if="loading4" role="status">
                                        <!-- <span class="sr-only">Loading...</span> -->
                                    </div>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <!--  -->
            </div>
        </div>
        <!-- right_section end here -->
    </div>
    </template>
<script>
    import SideBar from '@/Components/SideBar'
    import UserBar from '@/Components/UserBar'
    export default {
        components: {
            SideBar,
            UserBar
        },
        props: ['profile', 'user', 'bankDetails'],
        data() {
            return { 
                success: "",             
                error: "",                
                loading: false, 
                loading2: false, 
                loading3: false,
                loading4:false,
                c_password: "",
                n_password: "",
                form: { 
                    first_name: "",
                    last_name: "",
                    address: "",
                    city: "",
                    state: "",
                    zipcode : "",
                    latitude: "",                                       
                    longitude: "",                                                 
                },
                bank:{
                    account_number: (this.bankDetails)? this.bankDetails.account_number: "",
                    routing_number: (this.bankDetails)? this.bankDetails.routing_number: "",
                    account_holder_name: (this.bankDetails)? this.bankDetails.account_holder_name: "",
                }
            }
        },
        methods: {   
            bankSubmission: function (data) {
                console.log(this.formm, "fdfdfd", data);
                this.error = "";
                this.success = "";     
                this.loading4 = true;  
                console.log('this.form', this.bank)         
                axios.post('/save-bank', this.bank)
                    .then(response => {
                        this.loading4 = false;
                        if(response.data.status) {
                            this.success = response.data.message
                        } else {
                            this.error = "Please try again"
                        }
                })
                .catch(error => {
                    this.loading4 = false;
                    this.error = "Please try again";
                    console.log(error);
                })
                .then(() => {
                    this.loading4 = false;
                    window.scrollTo(0,0);
                }) 

            }, 
            accountSubmit: function (data) {
               this.error = "";
               this.success = "";     
               this.loading = true;  
               console.log('this.form', this.form)         
               axios.post('/update-account', this.form)
                    .then(response => {
                        if(response.data.status) {
                           this.success = response.data.message
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
                        window.scrollTo(0,0);
                    })              
            },
            passwordSubmit: function (data) {
               this.error = "";
               this.success = "";     
               this.loading2 = true;  
               console.log('this.form', this.form)         
               axios.post('/update-password', {c_password : this.c_password, n_password: this.n_password})
                    .then(response => {
                        if(response.data.status) {
                           this.success = response.data.message
                        } else {
                           this.error = response.data.message || "Please try again"
                        }
                    })
                    .catch(error => {
                        this.error = "Please try again";
                        console.log(error);
                    })
                    .then(() => {
                        this.loading2 = false;
                        window.scrollTo(0,0);
                    })              
            },  
            connectStripeAccount: function () {
                this.error = "";
                this.success = "";     
                this.loading3 = true;  
               console.log('this.form', this.form)         
               axios.post('/connected-account', {})
                .then(response => {
                    console.log(response.data);
                    
                    if(response.data.url) {
                        window.location.href = response.data.url;
                    } else {
                        this.error = response.data.message || "Please try again"
                    }
                })
                .catch(error => {
                    this.error = "Please try again";
                    console.log(error);
                })
                .then(() => {
                    this.loading3 = false;
                    window.scrollTo(0,0);
                })
            },        
            strippedContent: function(string) {
                return string.replace(/<\/?[^>]+>/ig, " "); 
            }, 
        },
        mounted() { 

            console.log('this.profile', this.bank);
            if(this.profile) {
                this.form = this.profile
                this.form.specialize = this.profile.specialization;
                this.form.insurances = this.profile.insurance;
                this.form.latitude = this.profile.latitude;
                this.form.longitude = this.profile.longitude;
                this.form.male = (this.profile.patients_gender) ? this.profile.patients_gender.male : ""
                this.form.female = (this.profile.patients_gender) ? this.profile.patients_gender.female : ""
                this.form.other =(this.profile.patients_gender) ? this.profile.patients_gender.other : ""
            }

            const autocomplete = new google.maps.places.Autocomplete(this.$refs["origin"]);
            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();
                
                if(place.geometry && place.geometry.location) {
                    this.form.latitude = place.geometry.location.lat();
                    this.form.longitude = place.geometry.location.lng();
                    this.form.address = this.strippedContent(place.adr_address);
                    this.form.city = place.address_components[1].short_name;
                    this.form.state = place.address_components[1].short_name;
                    // this.form.city = place.address_components[1].short_name;
                    // this.form.address = address;
                    var addressComponent = place.address_components;            
                    for (var x = 0 ; x < addressComponent.length; x++) {
                        var chk = addressComponent[x];
                        if (chk.types[0] == 'postal_code') {
                            this.form.zipcode = chk.long_name;
                        }
                        if (chk.types[0] == 'administrative_area_level_1') {
                            this.form.state = chk.long_name;
                        }
                    }

                }
            });
        }
    }
</script>