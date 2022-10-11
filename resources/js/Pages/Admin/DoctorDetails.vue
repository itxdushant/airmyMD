    <template>
    <Head title="Profile" />
        <div class="main_body">
            <AdminSideBar></AdminSideBar>
            <!-- left_bar end here -->
            <div class="right_section">
                <div class="mobile_header d-block d-lg-none">
                    <div class="mobile_flex">
                        <a href="#"><img src="/images/logo.png"></a>
                        <div class="toggle_icon">
                            <img src="/images/toggle.png">
                        </div>
                    </div>
                </div>
                <div class="login_right_section">
                    <div class="top_bar_c">
                        <h3 class="h3_tittle">Profile</h3>
                        <div class="right_top_input">
                            <label for="search">
                                <input type="search" name="search">
                                <!-- <img src="images/search_1.png"> -->
                            </label>
                            <UserBar/>
                        </div>
                    </div>
                    <form @submit.prevent="submit">
                        <div class="row mt-4">
                            <div v-if="success" class="alert alert-success text-dark font-weight-bold mt-4">
                                {{ success }}
                            </div>
                            <div v-if="error" class="alert alert-warning text-dark font-weight-bold mt-4">
                                {{ error }}
                            </div>
                            <div class="col-md-7 left_div">
                                <h4 class="h4_tittle">Profile Image</h4>
                                <div class="upload_img_div">
                                    <img :src="form.image" v-if="form.image" style="width: 80px;height: 80px;border-radius: 50%;display: list-item;margin-top: 10px;" />
                                    <div v-if="!form.image">
                                        <img :src="doctor.profile_photo_url" v-if="doctor.profile_photo_url" style="width: 80px;height: 80px;border-radius: 50%;display: list-item;margin-top: 10px;" />
                                    </div>
                                </div>
                                <div class="bio_div">
                                    <h4 class="h4_tittle">Bio</h4>
                                    <textarea placeholder="Bio" v-model="form.bio" disabled>{{ profile?profile.bio : ''}}</textarea>
                                </div>
                                <div class="bio_div">
                                    <h4 class="h4_tittle">Appointment Fee ($)</h4>
                                    <input type="text" disabled name="fees" class="card-input-element" id="fees" v-model="form.fees">
                                </div>
                                <div class="specialization">
                                    <h4 class="h4_tittle">Areas of Specialization</h4>
                                    <div class="specialization_card">
                                        <div class="box_card">
                                            <label>
                                                <input type="checkbox" disabled name="product" class="card-input-element" value="Primary Care Primary Care Physician" v-model="form.specialize">
                                                <div class="panel panel-default card-input">
                                                    <img src="uploads/specializations/icon_physician.png">
                                                    <h5>Primary Care Physician</h5>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="box_card">
                                            <label>
                                                <input type="checkbox" disabled name="product" class="card-input-element" value="Dentists" v-model="form.specialize">
                                                <div class="panel panel-default card-input">
                                                    <img src="uploads/specializations/icon_dentist.png">
                                                    <h5>Dentists</h5>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="box_card">
                                            <label>
                                                <input type="checkbox" disabled name="product" class="card-input-element" value="Internists" v-model="form.specialize">
                                                <div class="panel panel-default card-input">
                                                    <img src="uploads/specializations/icon_internists.png">
                                                    <h5>Internists</h5>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="box_card">
                                            <label>
                                                <input type="checkbox" disabled name="product" class="card-input-element" value="OBGYN" v-model="form.specialize">
                                                <div class="panel panel-default card-input">
                                                    <img src="uploads/specializations/icon_obgyn.png">
                                                    <h5>OBGYN</h5>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- <div class="specialization_card">
                                        <div class="box_card">
                                            <label>
                                                <input type="checkbox" disabled name="product" class="card-input-element" value="Dermatologists" v-model="form.specialize">
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
                            </div>
                            <div class="col-md-5 right_div">
                                <div class="telemedicine">
                                    <h4 class="h4_tittle">Do you currently offer telemedicine?</h4>
                                    <div class="inputs_telemedicine">
                                        <label class="frst_lebal">
                                            <input type="radio" disabled value="Yes" v-model="form.telemedicine">Yes
                                        </label>
                                        <label>
                                            <input type="radio" disabled value="No, and not interested in offering" v-model="form.telemedicine">No, and not interested in offering
                                        </label>
                                    </div>
                                    <div class="inputs_telemedicine">
                                        <label>
                                            <input type="radio" disabled value="No, but interested in offering" v-model="form.telemedicine">No, but interested in offering
                                        </label>
                                    </div>
                                </div>
                                <div class="insurances ">
                                    <h4 class="h4_tittle">What insurances do you accept? </h4>
                                    <div class="inputs_telemedicine insurances_inputs profile_insurances">
                                        <div class="check_inputs_all">
                                            <form>
                                                <label>
                                                    <input type="checkbox" disabled value="Blue Cross Blue Shield" v-model="form.insurances">Blue Cross Blue Shield
                                                </label>
                                                <label>
                                                    <input type="checkbox" disabled value="United Healthcare" v-model="form.insurances">United Healthcare
                                                </label>
                                                <label>
                                                    <input type="checkbox" disabled value="HealthPartners" v-model="form.insurances">HealthPartners
                                                </label>
                                                <label>
                                                    <input type="checkbox" disabled value="Kaiser Foundation" v-model="form.insurances">Kaiser Foundation
                                                </label>
                                                <label>
                                                    <input type="checkbox" disabled value="Anthem, Inc." v-model="form.insurances">Anthem, Inc.
                                                </label>
                                                <label>
                                                    <input type="checkbox" disabled value="Centene Corp" v-model="form.insurances">Centene Corp
                                                </label>
                                                <label>
                                                    <input type="checkbox" disabled value="Humana" v-model="form.insurances">Humana
                                                </label>   
                                            </form>
                                        </div>
                                        <div class="check_inputs_all ">
                                            <form>
                                                <label>
                                                    <input type="checkbox" disabled value="Molina Healthcare, Inc." v-model="form.insurances">Molina Healthcare, Inc.
                                                </label>
                                                <label>
                                                    <input type="checkbox" disabled value="CVS" v-model="form.insurances">CVS
                                                </label>
                                                <label>
                                                    <input type="checkbox" disabled value="Independence Health Group" v-model="form.insurances">Independence Health Group
                                                </label>
                                                <label>
                                                    <input type="checkbox" disabled value="HCSC" v-model="form.insurances">HCSC
                                                </label>
                                                <label>
                                                    <input type="checkbox" disabled value="Aetna" v-model="form.insurances">Aetna
                                                </label>
                                                <label>
                                                    <input type="checkbox" disabled value="Cigna Health" v-model="form.insurances">Cigna Health
                                                </label>
                                                <label>
                                                    <input type="checkbox" disabled value="Other" v-model="form.insurances" @change="insuranceHandler($event)">Other
                                                </label>
                                                <div class="insurances_rep" v-if="others">
                                                    <div  v-for="(doc,index) in form.other_insurance" :key="index" class="other_in">
                                                        <input type="text" v-model="form.other_insurance[index]"/>
                                                        <span v-if="index > 0 " @click="removeInsurance(index)">X</span>
                                                        <span v-else @click="repeatInsurance($event)">+</span>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="patient_gender">
                                    <div v-if="doctor.status == 0">
                                        <a href="#" class="btn_view_details btn_acpt" @click="accept(doctor)">Approve</a> &nbsp;
                                        <a href="#" class="btn_view_details btn_rjct" @click="reject(doctor)">Disapprove</a> &nbsp;
                                    </div>
                                    <div v-else-if="doctor.status == 1">
                                        <a href="#" class="btn_view_details active approved">Approved</a> &nbsp;
                                        <a href="#" class="btn_view_details btn_rjct" @click="reject(doctor)">Disapprove</a> &nbsp;
                                    </div>
                                    <div v-else>
                                        <a href="#" class="btn_view_details btn_acpt" @click="accept(doctor)">Approve</a> &nbsp;
                                        <a href="#" class="btn_view_details active disapproved">Disapproved</a> &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--  -->
                </div>
            </div>
            <!-- right_section end here -->
        </div>
        </template>
    <style>
        a.btn_view_details.active.approved {
            background: #275809;
            color: #fff;
        }
        a.btn_view_details.active.disapproved {
            background: #ff3838;
            color: #fff;
        }
    </style>
    <script>
        import AdminSideBar from '@/Components/AdminSideBar'
        import UserBar from '@/Components/UserBar'
        export default {
            components: {
                AdminSideBar,
                UserBar
            },
            props: ['profile', 'user', 'doctor'],
            
            data() {
                return { 
                    success: "",             
                    error: "",                
                    loading: false,  
                    others: (this.profile.insurance.includes("Other"))? true:false,
                    form: { 
                        specialize: [],                                                            
                        //insurances: [],   
                        male: "",
                        female: "",
                        other: "",                                                         
                        image: "",
                        insurances: this.profile.insurance,
                        other_insurance: (this.profile.other_insurance)? this.profile.other_insurance: ['']   
                        //other_insurance: (this.profile.other_insurance)? this.profile.other_insurance: ['']                                                      
                    },
                }
            },
            methods: {    
                repeatInsurance(e){
                    this.form.other_insurance.push("");
                },
                removeInsurance(index){
                    this.form.other_insurance.splice(index, index);  
                },
                accept(data) {              
                    this.success = "";         
                    axios.post('/approve-doctor', { id: data.id })
                    .then(response => {
                        this.success = response.data.message;
                        this.doctor.status = 1;

                        // this.getData();
                    })
                    .catch(error => {
                        console.log(error);
                    })                                 
                },
                reject(data) {
                    this.success = "";
                    axios.post('/admin/disapprove-doctor', { id: data.id })
                    .then(response => {
                        this.success = response.data.message;
                        this.doctor.status = 2;
                    })
                    .catch(error => {
                        console.log(error);
                    })              
                },
                onFileChange(e) {
                    var files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    this.createImage(files[0]);
                },        
                createImage(file) {
                    // console.log(file)
                    var image = new Image();
                    var reader = new FileReader();
                    var vm = this;

                    reader.onload = (e) => {
                        vm.form.image = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },
                submit: function (data) {
                this.error = "";
                this.success = "";     
                this.loading = true;  
                console.log('this.form', this.form)         
                axios.post('/admin/update-profile', this.form)
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
            },
            mounted() { 
                
                if(this.profile) {
                    this.form = this.profile
                    this.form.specialize = this.profile.specialization
                    this.form.insurances = this.profile.insurance
                    this.form.male = (this.profile.patients_gender) ? this.profile.patients_gender.male : ""
                    this.form.female = (this.profile.patients_gender) ? this.profile.patients_gender.female : ""
                    this.form.other =(this.profile.patients_gender) ? this.profile.patients_gender.other : ""
                }

                if(this.form.other_insurance.length < 1){
                    this.form.other_insurance.push("");
                }

                console.log(this.form, "sdsads");

            }
        }
    </script>