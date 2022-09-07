<template>
    <Head title="Create profile" />
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
                    <form @submit.prevent="submit" autocomplete="off"> 
                        <h3 class="h3_tittle">Create profile</h3>
                        <div v-if="success" class="alert alert-success text-dark font-weight-bold mt-4">
                           {{ success }}
                        </div>
                        <div v-if="error" class="alert alert-warning text-dark font-weight-bold mt-4">
                           {{ error }}
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" placeholder="First Name" v-model="form.first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" placeholder="Last Name" v-model="form.last_name" name="last_name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <input type="text" placeholder="Address" v-model="form.address" name="address" ref="origin" required autocomplete="off">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" placeholder="City" v-model="form.city" name="city" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="State" v-model="form.state" name="state" required/>
                                <!-- <select id="" v-model="form.state" name="state" required>
                                    <option value="">State</option>
                                    <option value="AL">Alabama</option>
                                    <option value="AK">Alaska</option>
                                    <option value="AZ">Arizona</option>
                                    <option value="AR">Arkansas</option>
                                    <option value="CA">California</option>
                                    <option value="CO">Colorado</option>
                                    <option value="CT">Connecticut</option>
                                    <option value="DE">Delaware</option>
                                    <option value="DC">District Of Columbia</option>
                                    <option value="FL">Florida</option>
                                    <option value="GA">Georgia</option>
                                    <option value="HI">Hawaii</option>
                                    <option value="ID">Idaho</option>
                                    <option value="IL">Illinois</option>
                                    <option value="IN">Indiana</option>
                                    <option value="IA">Iowa</option>
                                    <option value="KS">Kansas</option>
                                    <option value="KY">Kentucky</option>
                                    <option value="LA">Louisiana</option>
                                    <option value="ME">Maine</option>
                                    <option value="MD">Maryland</option>
                                    <option value="MA">Massachusetts</option>
                                    <option value="MI">Michigan</option>
                                    <option value="MN">Minnesota</option>
                                    <option value="MS">Mississippi</option>
                                    <option value="MO">Missouri</option>
                                    <option value="MT">Montana</option>
                                    <option value="NE">Nebraska</option>
                                    <option value="NV">Nevada</option>
                                    <option value="NH">New Hampshire</option>
                                    <option value="NJ">New Jersey</option>
                                    <option value="NM">New Mexico</option>
                                    <option value="NY">New York</option>
                                    <option value="NC">North Carolina</option>
                                    <option value="ND">North Dakota</option>
                                    <option value="OH">Ohio</option>
                                    <option value="OK">Oklahoma</option>
                                    <option value="OR">Oregon</option>
                                    <option value="PA">Pennsylvania</option>
                                    <option value="RI">Rhode Island</option>
                                    <option value="SC">South Carolina</option>
                                    <option value="SD">South Dakota</option>
                                    <option value="TN">Tennessee</option>
                                    <option value="TX">Texas</option>
                                    <option value="UT">Utah</option>
                                    <option value="VT">Vermont</option>
                                    <option value="VA">Virginia</option>
                                    <option value="WA">Washington</option>
                                    <option value="WV">West Virginia</option>
                                    <option value="WI">Wisconsin</option>
                                    <option value="WY">Wyoming</option>
                                </select> -->
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="Zip Code" v-model="form.zipcode" name="zipcode" required>
                            </div>
                        </div>
                        <div class="btn_update update_btn text-right">
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
        props: [],
        data() {
            return { 
                success: "",             
                error: "",                
                loading: false,  
                form: { 
                    first_name: "",
                    last_name: "",  
                    address: "",                                     
                    city: "",                                       
                    state: "",                                       
                    zipcode: "",                                       
                    latitude: "",                                       
                    longitude: "",                                       
                },
            }
        },
        methods: {    
            strippedContent: function(string) {
                return string.replace(/<\/?[^>]+>/ig, " "); 
            },        
            submit: function (data) {
               this.error = "";
               this.success = "";     
               this.loading = true;           
               axios.post('/create-profile', this.form)
                    .then(response => {
                        if(response.data.status) {
                           this.success = response.data.message
                           setTimeout(() => {
                            this.$inertia.visit('/profile-bio');
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
            const autocomplete = new google.maps.places.Autocomplete(this.$refs["origin"]);
            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();
                console.log(place);
                
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