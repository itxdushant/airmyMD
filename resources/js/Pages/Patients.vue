<template>
    <div class="main_body">
         <SideBar></SideBar>
         <!-- left_bar end here -->
         <div class="right_section">
            <div class="mobile_header d-block d-lg-none">
               <div class="mobile_flex">
                  <a href="#"><img src="images/logo.png"></a>
                  <div class="toggle_icon">
                     <img class="toggle_menu" src="images/toggle.png">
                  </div>
               </div>
            </div>
            <div class="my_appointments_right">
               <div class="top_bar_c">
                  <h3 class="h3_tittle">Patients</h3>
                  <div class="right_top_input">
                     <!-- <div class="list_view_div">
                        <span class=" card_view pe-3">List View</span>
                        <label class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                        </label>
                        <span class="list_view">Card View </span>
                     </div> -->
                     <!-- <label for="search">
                        <input type="search" name="search">
                     </label> -->
                     <UserBar/>
                  </div>
               </div>
               <div class="search_filter_div">
                  <div class="search">
                     <input type="search" placeholder="Search by Name" @keyup="filterByKeyword($event)">
                  </div>
                  <!-- <div class="selct_input appt">
                     <select name="" id="">
                        <option value="">Appt. Type </option>
                        <option value="">Appt. Type 2</option>
                        <option value="">Appt. Type 3</option>
                        <option value="">Appt. Type 4</option>
                     </select>
                  </div>
                  <div class="selct_input visit_reason">
                     <select name="" id="">
                        <option value="">Visit Reason</option>
                        <option value="">Visit Reason 1</option>
                        <option value="">Visit Reason 2</option>
                        <option value="">Visit Reason 2</option>
                     </select>
                  </div> -->
                  <!-- <div class="selct_input date">
                     <input type="date" placeholder="FIlter by Date">
                  </div> -->
               </div>

               <div class="patients_section">
                  <div class="row text-center" v-if="loading">
                     <div class="spinner-border" role="status" style="margin:50px auto;">
                     </div>
                  </div>
                  <div class="patients_card_row" v-if="!loading">
                     <div class="col-20" v-for="(doc,index) in patients.data" :key="index">
                        <div class="card_patients">
                           <img :src="(doc.profile_photo_url)? doc.profile_photo_url: ''">
                           
                           <h4>{{(doc.patient_profile)? doc.patient_profile.first_name: ''}} {{(doc.patient_profile)? doc.patient_profile.last_name: ''}}</h4>
                           <!-- <p>Charlotte Little</p> -->
                           <a :href="histryLink((doc.patient_profile)?doc.patient_profile: '' )" class="btn_history">History</a>
                           <a :href="patientLink(doc)" class="btn_history">View</a>
                        </div>
                     </div>                     
                  </div>
                  <div v-if="patients.data.length == 0 && !loading">
                     <div class="alert alert-warning">
                        <strong>No Data Found.</strong>
                     </div>
                  </div>
               </div>
               <div class="pagination_div">
                  <Pagination :data="patients" @pagination-change-page="list" />
               </div>
            </div>
            
         </div>
         <!-- right_section end here -->
      </div>
</template>
<script>
   import SideBar from '@/Components/SideBar'
   import UserBar from '@/Components/UserBar'
   import LaravelVuePagination from 'laravel-vue-pagination';

   export default {
      components: {
         SideBar,
         UserBar,
         'Pagination': LaravelVuePagination
      },
      props: [],
      data() {
         return { 
            loading: true, 
            patients: {
               data: []
            }, 
            page: 1,    
            timer: null,
            keyword: "",
         }
      },
      methods: {  
         histryLink(profile){
            if(profile){
               return '/appointment-history?'+profile.first_name;
            }
            console.log(profile, "testing");
         },
         patientLink(doc){
            if(doc){
               return '/patient/'+doc.id;
            }
         },
         list(page = 1) {
            this.page = page;
            this.getData();
         },
         filterByKeyword(event) {               
            this.page = 1;
            this.keyword = event.target.value;
            if (this.timer) {
               clearTimeout(this.timer);
               this.timer = null;
            }
            this.timer = setTimeout(() => {
               this.getData();
            }, 800);            
         },    
         getData() {
            this.loading = true;
            axios.get(`/patients-list?page=${this.page}&keyword=${this.keyword}`).then(({data}) => {                     
               this.patients = data
               console.log(data.data, "dfdfdfd");
               this.loading = false;
            }).catch(({ response })=>{
               this.loading = false;
               console.error(response)
            })
         },
      },
      mounted() { 
         this.list();
         console.log(this.patients.data);
      }
    }
</script>