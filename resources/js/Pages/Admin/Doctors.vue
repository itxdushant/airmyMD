<template>
    <div class="main_body">
         <AdminSideBar></AdminSideBar>
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
                  <h3 class="h3_tittle">Doctors List</h3>
                  <div class="right_top_input">
                     
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
                  <div class="selct_input date">
                     <input type="date" placeholder="FIlter by Date">
                  </div>
               </div>
               <div  class="modal-vue">
                  <div class="overlay" v-if="showModal" @click="showModal = false"></div>
                  <div class="vue-modal" v-if="showModal">
                     <button class="close" @click="showModal = false">x</button>
                     <p><b>Appointment Date: </b> <span>{{(bookingDetails)? bookingDetails.booking_date: ''}}</span></p>
                     <p><b>Appointment Time: </b> <span>{{bookingDetails.booking_time}}</span></p>
                     <p><b>Appointment Status: </b> <span>{{bookingDetails.status}}</span></p>
                     <p><b>Appointment Status Reason: </b> <span>{{bookingDetails.reason}}</span></p>
                     <p><b>Payment Type: </b> <span>{{bookingDetails.payment_type}}</span></p>
                     <p><b>Doctor Name: </b> <span>{{bookingDetails.source_name}}</span></p>
                     <p><b>Patient Name: </b> <span>{{bookingDetails.user.patient_profile.first_name}} {{bookingDetails.user.patient_profile.last_name}}</span></p>

                  </div>
               </div>
               <div>                  
                  <div class="table_section">
                     <div v-if="success" class="alert alert-success text-dark font-weight-bold mt-4">
                        {{ success }}
                     </div>
                     <div v-if="error" class="alert alert-warning text-dark font-weight-bold mt-4">
                        {{ error }}
                     </div>
                     <table role="table">
                        <thead role="rowgroup">
                           <tr role="row">
                              <th role="columnheader">Doctor Name</th>
                              <!-- <th role="columnheader">Specialization</th> -->
                              <th role="columnheader">Email</th>
                              <th role="columnheader">Status</th>
                           </tr>
                        </thead>
                        <tbody role="rowgroup" v-if="!loading">
                           <tr role="row" v-for="(doc,index) in doctors.data" :key="index">
                              <td role="cell">{{(doc.profile)? doc.profile.first_name: ''}} {{(doc.profile)? doc.profile.last_name:''}}</td>
                              <!-- <td role="cell">{{(doc.profile)? doc.profile.specialization:'' }}</td> -->
                              <td role="cell">{{doc.email}}</td>
                              <td role="cell" v-if="doc.status == 0">Inactive</td>
                              <td role="cell" v-else-if="doc.status == 1">active</td>
                              <td role="cell" v-else>Declined</td>
                              <td role="cell" class="btn_acept_rejct">
                                 <div v-if="doc.status == 0">
                                    <a href="#" class="btn_view_details btn_acpt" @click="accept(doc)">Approve</a> &nbsp;
                                    <a href="#" class="btn_view_details btn_rjct" @click="reject(doc)">Disapprove</a> &nbsp;
                                 </div>
                                 <div v-else-if="doc.status == 1">
                                    <a href="#" class="btn_view_details active approved">Approved</a> &nbsp;
                                    <a href="#" class="btn_view_details btn_rjct" @click="reject(doc)">Disapprove</a> &nbsp;
                                 </div>
                                 <div v-else>
                                    <a href="#" class="btn_view_details btn_acpt" @click="accept(doc)">Approve</a> &nbsp;
                                    <a href="#" class="btn_view_details active disapproved">Disapproved</a> &nbsp;
                                 </div>
                                 <a href="#" class="btn_view_details" @click="openModal(doc)">View Details</a>
                              </td>
                           </tr>                        
                        </tbody>
                     </table>
                     
                     <div class="row text-center" v-if="loading">
                        <div class="spinner-border" role="status" style="margin:50px auto;">
                        </div>
                     </div>
                     <div v-if="doctors.data.length == 0 && !loading">
                        <div class="alert alert-warning">
                           <strong>No Data Found.</strong>
                        </div>
                     </div>
                  </div>                 
               </div>
               <div class="pagination_div">
                  <Pagination :data="doctors" @pagination-change-page="list" />
               </div>
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
   import LaravelVuePagination from 'laravel-vue-pagination';
   

   export default {
      components: {
         AdminSideBar,
         UserBar,
         'Pagination': LaravelVuePagination
      },
      props: [''],
      data() {
         return {
            editMode: false,
            doctors: {
               data: []
            },
            showModal: null, 
            loading: true,
            success: "",             
            error: "",    
            bookingDetails: null,
            page: 1,    
            timer: null,
            keyword: "",
         }
      },
      methods: {  
         openModal(data) {
            window.location.href = '/doctor-details/'+data.id;
         },
         closeModal() {
               this.showModal = null;
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
            axios.get(`/doctor-filter?page=${this.page}&keyword=${this.keyword}`).then(({data}) => {                     
               this.doctors = data;
               //console.log(this.doctors, "sdfdsf");
               this.loading = false;
            }).catch(({ response })=>{
               this.loading = false;
               console.error(response)
            })
         },
         accept(data) {    
            console.log(data);          
            this.success = "";         
            axios.post('/approve-doctor', { id: data.id })
              .then(response => {
                  this.success = response.data.message;
                  data.status = 1;
                  // this.getData();
              })
              .catch(error => {
                  console.log(error);
              })                                 
         },
         list(page = 1) {
            this.page = page;
            this.getData();
         },
         reject(data) {
            //this.success = "";
            axios.post('/disapprove-doctor', { id: data.id })
              .then(response => {
                  this.success = response.data.message;
                  data.status = 2;
              })
              .catch(error => {
                  console.log(error);
              })              
         }, 
      },
      mounted() { 
         this.list();
         console.log(this.doctors, "doctors");
      }
    }
</script>