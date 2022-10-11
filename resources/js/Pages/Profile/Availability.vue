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
      <div class="my_appointments_right transaction_section">
         <div class="top_bar_c">
            <h3 class="h3_tittle">Availability</h3>
            <div class="right_top_input">
               <UserBar/>
            </div>
         </div>
         <div class="search_filter_div">
            
         </div>
         <!-- table -->
         <div class="table_section transaction_table">
            <div class="Application-new-main">
               <div class="rightside-form">
                  <div v-if="success" class="alert alert-success text-dark font-weight-bold mt-4">
                      {{ success }}
                  </div>
                  <div v-if="error" class="alert alert-warning text-dark font-weight-bold mt-4">
                      {{ error }}
                  </div>
                  <form method="post" id="datesForm">
                     <div class="name-main2">
                        <h3>Hours of Operation</h3>
                        <div class="mt-45">
                           <div class="row" v-for="(key,indexMain) in days" :key="indexMain">
                              <div class="mb-30 col-md-10">
                                 <div class="time_table">
                                    <div class="ist_div">
                                       <div class="form-switch">
                                          <input type="checkbox" id="custom-switch" :checked="key.status" @change="changeDay(key)" class="form-check-input sunday" :value="key.day">
                                          <label title="" for="custom-switch" class="form-check-label"></label></div>
                                          <div class="weekend">
                                             <p><b>{{key.day}}</b></p>
                                          </div>
                                       </div>
                                       <div class="" v-if="key.status">
                                          <div class="nd_div sunday-val" v-for="(time,index) in key.time" :key="index">
                                             <div class="custom_time">
                                                <div class="row">
                                                   <div class="t_tm col-md-3">
                                                      <input type="time" name="start_time" pattern="^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$" class="form-control start_time" v-model="time.start_time" min="1">
                                                      <div class="arrows-wrapper"></div>
                                                   </div>
                                                   <div class="to col-md-1 text-center">To</div>
                                                   <div class="t_tm col-md-3">
                                                      <input type="time" name="end_time" pattern="^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$" class="form-control end_time" v-model="time.end_time">
                                                      <div class="arrows-wrapper"></div>
                                                   </div>
                                                   <div class="to col-md-1 text-center" v-if="index > 0 "><span @click="removeTime(indexMain, index)">X</span></div>
                                                   <a href="javascript:void(0)" class="add_time to col-md-1 text-center" @click="addTimeSlots(indexMain)" v-if="index == 0 ">+</a>
                                                </div>
                                             </div> 
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>                              
                              <div class="row"></div>
                           </div>
                        </div>
                        <div class="name-main2 mt-4">
                           <h3>Add days that your office is not available, such as holidays</h3>
                           <div class="mt-45 special">
                             <div class="row" v-for="(key,index) of special_days" :key="index">
                                 <div class="mb-2 col-md-10">
                                    <div class="time_table">
                                       <div class="nd_div">
                                          <div class="row">
                                             <div class="t_tm col-md-3">
                                                <input type="date" id="custom-date" class="custom-date form-control" v-model="key.date" :min="currenyDate">
                                             </div>
                                             <!-- <div class="t_tm col-md-3">
                                                <input type="time" name="start_time" class="form-control start_time" v-model="key.start_time">
                                                <div class="arrows-wrapper"></div>
                                             </div>
                                             <div class="to col-md-1 text-center">To</div>
                                             <div class="t_tm col-md-3">
                                                <input type="time" name="end_time" class="form-control end_time" v-model="key.end_time">
                                                <div class="arrows-wrapper"></div>
                                             </div> -->
                                             <div class="to col-md-1 text-center" v-if="index > 0 "><span @click="removeDays(index)">X</span></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>                 
                             </div>
                           </div>
                           <button type="button" class="btn btn-sm btn-info mt-2" id="addmore" @click="addDays">ADD DAYS</button>
                        </div>
                        <div class="loader"></div>
                        <div class="name-main2 mt-4">                           
                           <button type="button" class="btn btn-success" @click="saveDays">Update</button>
                        </div>                       
                     </form>
                  </div>
                  
               </div>
            </div>
            
         </div>
      </div>
      <!-- right_section end here -->
   </div>
</template>
<script>
   import SideBar from '@/Components/SideBar'
   import UserBar from '@/Components/UserBar'
   import moment from 'moment'
   export default {
      components: {
         SideBar,
         UserBar
      },
      props: ['profile', 'user'],
      data() {
         return { 
            success: "",             
            error: "",                
            loading: false,  
            days : [
               {
                  day: 'Sunday',
                  currenyDate: moment(new Date()).format("YYYY-MM-DD"),
                  status: false,
                  time: [{ 
                     start_time: '',
                     end_time: '',
                  }]
               },{
                  
                  day: 'Monday',
                  status: false,
                  time: [{
                     start_time: '',
                     end_time: '',
                  }]
               },{
                  day: 'Tuesday',
                  status: false,
                  time: [{
                     start_time: '',
                     end_time: '',
                  }]
               },{
                  day: 'Wednesday',
                  status: false,
                  time: [{
                     start_time: '',
                     end_time: '',
                  }]
               },{
                  day: 'Thursday',
                  status: false,
                  time:[{
                     start_time: '',
                     end_time: '',
                  }]
               },{
                  day: 'Friday',
                  status: false,
                  time: [{
                     start_time: '',
                     end_time: '',
                  }]
               },{
                  day: 'Saturday',
                  status: false,
                  time: [{
                     start_time: '',
                     end_time: '',
                  }]
               },
            ],
            special_days: [{
                  date: ''
               },
            ]

         }
      },
      methods: {     
         addTimeSlots(primary ){
            this.days[primary].time.push({
               start_time: '',
               end_time: '',
            });
         },
         
         changeDay(key) {
            key.status = !key.status;
         },
         addDays() {
            this.special_days.push({
               date: '',
            });
         },
         removeDays: function(index) {
            this.special_days.splice(index, index);  
         },
         removeTime: function(primary, index) {
            this.days[primary].time.splice(index, 1);  
         },
         saveDays() {
            this.error = "";
            this.success = "";     
            this.loading = true;  
            const data = {
               days: this.days,
               special_days: this.special_days,
            }
            //console.log('this.form', data); return false;      
            axios.post('/availability', data)
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
         }


      },
      mounted() { 
         if(this.profile) {
            if(this.profile.days) {
               this.days = this.profile.days;
            }
            if(this.profile.special_days) {
               this.special_days = this.profile.special_days;
            }
         }

         console.log('moment', this.currenyDate);
      }
    }
</script>