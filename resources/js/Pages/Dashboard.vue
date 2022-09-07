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
            <div class="dashboard_right">
               <div class="top_bar_c">
                  <h3 class="h3_tittle">Dashboard</h3>
                  <span class="verify_account" v-if="!data.verify.availability || !data.verify.bank">Complete your profile for approval: <a href="/availability" v-if="!data.verify.availability">Availability </a> {{(!data.verify.availability && !data.verify.bank)? ',':''}} <a href="/account" v-if="!data.verify.bank"> Banking information</a></span>
                  <div class="right_top_input">
                     <!-- <label for="search">
                        <input type="search" name="search">
                        <img src="images/search_1.png">
                     </label> -->
                     <UserBar/>
                  </div>
               </div>
               <div class="row mt-4">
                  <div class="col-md-8">
                     
                     <h4 class="h4_tittle">Stats</h4>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="card_stats">
                              <h4>Appointment Statistics</h4>
                              <span>{{(data.appointment_statistics)?data.appointment_statistics: 0 }}%</span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="card_stats">
                              <h4>Upcoming Appointments</h4>
                              <span>{{(data.upcoming_booking)?data.upcoming_booking: 0 }}</span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="card_stats">
                              <h4>Money Made this Month</h4>
                              <span>${{(data.earnings)?data.earnings: 0 }}</span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="card_stats">
                              <h4>Total Patients </h4>
                              <span>{{(data.total_patients)?data.total_patients: 0 }}</span>
                           </div>
                        </div>
                     </div>
                     <div class="recent_message_div">
                        <div  class="modal-vue">
                           <div class="overlay" v-if="showModal" @click="showModal = false"></div>
                           <div class="vue-modal" v-if="showModal">
                              <button class="close" @click="showModal = false">x</button>
                              <p><b>Patient Name: </b> <span><a :href="'/patient/'+bookingDetails.user_id">{{bookingDetails.user.patient_profile.first_name}} {{bookingDetails.user.patient_profile.last_name}}</a></span></p>
                              <p><b>Appointment Date: </b> <span>{{(bookingDetails)? moment(bookingDetails.booking_date): ''}}</span></p>
                              <p><b>Appointment Time: </b> <span>{{bookingDetails.booking_time}}</span></p>
                              <p><b>Appointment Status: </b> <span>{{bookingDetails.status}}</span></p>
                              <p><b>Appointment Status Reason: </b> <span>{{bookingDetails.reason}}</span></p>
                              <p><b>Payment Type: </b> <span>{{bookingDetails.payment_type}}</span></p>                     
                           </div>
                        </div>
                        <h4 class="h4_tittle">Recent Messages</h4>
                        <div class="all_messages" v-if="data.latest_msg">
                           <div class="messge_div" v-for="(msg, index) in data.latest_msg" :key="index">
                              <h5>
                                 {{(msg.sent.patient_profile)? msg.sent.patient_profile.first_name: ''}}
                                 {{(msg.sent.patient_profile)? msg.sent.patient_profile.last_name: ''}}
                              </h5>
                              <p>{{msg.message}}</p>
                           </div>
                           <!-- <div class="messge_div">
                              <h5>Marion Bishop</h5>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod…</p>
                           </div>
                           <div class="messge_div">
                              <h5>Christopher Allison</h5>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod…</p>
                           </div>
                           <div class="messge_div">
                              <h5>Ivan Garrett</h5>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod…</p>
                           </div> -->
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <h4 class="h4_tittle">Appointments</h4>
                     <div class="calender_div">
                        <!-- <calendar-view
                           :show-date="showDate" display-period-uom="month" :month-name-format="narrow"
                           class="theme-default holiday-us-traditional holiday-us-official"
                        @click-event="onClickEvent">
                           <calendar-view-header
                              :header-props="t.headerProps"
                              @input="setShowDate" />
                        </calendar-view> -->
                        <div class="calendar">
                           <div class="calendar__header">
                              <a href="javascript:;" class="arrow-btn btn-prevmonth" @click.prevent="changeMonth(false)">Prev</a>
                              <div class="calendar__title" @click.prevent="backToToday()">
                                 <span class="caption-month">{{this.convertTwoDigits()}} </span>
                                 <span class="caption-year">{{current.year}}</span>
                              </div>
                              <a href="javascript:;" class="arrow-btn btn-nextmonth" @click.prevent="changeMonth(true)">Next</a>
                           </div>
                           <div class="calendar__body">
                              <ul class="calendar__heading">
                                 <li v-for="item in heading" :key="item">
                                    <div class="calendar__item">{{item}}</div>
                                 </li>
                              </ul>
                              <ul class="calendar__content">
                                 <li v-for="item in buildCalendar()" :key="item">
                                    <a href="javascript:;" class="calendar__item" :class="{'is-today': item.today === true, 'current': item.current === true}" @click.prevent="getDateData(item)">{{item.number}}</a> <span class='event_aval' v-if="item.count > 0">.</span>
                                 </li>
                              </ul>
                           </div>
                        </div>
                        <div class="todolist">
                           <div class="todolist__header">
                              <span class="caption-month">{{this.convertTwoDigits()}} </span>
                                 <span class="caption-year">{{current.year}}</span>
                           </div>
                           <div class="todolist__body"></div>
                        </div>
                        <!-- <img src="images/calender_1.png" class="img-fluid"> -->
                     </div>
                     <div class="clients_cards_div" v-if="seletedDateAppointments">
                        <div class="card_clnt" v-for="(Appointments,index) in seletedDateAppointments" :key="index">
                           <div class="left_img_name" @click="openModal(Appointments)">
                              <img :src="(Appointments.user.profile_photo_url)? Appointments.user.profile_photo_url: 'images/n_2.png' ">
                              <h5>{{(Appointments.user.patient_profile.first_name)? Appointments.user.patient_profile.first_name: ''}} {{(Appointments.user.patient_profile.last_name)? Appointments.user.patient_profile.last_name: ''}}</h5>
                              <p><span class="date_a">{{(Appointments.booking_date)? moment(Appointments.booking_date): ''}} </span>|<span class="time_a"> {{Appointments.booking_time}}</span></p>
                           </div>
                           <!-- <a href="#" class=""><img src="images/next_arow.png"></a> -->
                        </div>
                        
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- right_section end here -->
      </div>
</template>
<style >
   @import url('https://fonts.googleapis.com/css?family=Rubik');
</style>
<script>
   import SideBar from '@/Components/SideBar'
   import UserBar from '@/Components/UserBar'
   import moment from 'moment'
    export default {
        components: {
            SideBar,
            UserBar,
        },
        props: ['data'],
        data() {
            return { 
               appointmentDates: null,
               showDate: new Date(),
               showModal:false,
               current: {
                  year: 0,
                  month: 0,
                  date: 0
               },
               today: {
                  year: 0,
                  month: 0,
                  date: 0
               },
               bookingDetails: null,
               heading: ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"],
               monthNames: ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"],
               seletedDateAppointments: null
            }
        },
        methods: { 
            checkCount(date){
               if(this.appointmentDates){
                  Object.entries(this.appointmentDates).forEach(([key, value], index) => {
                     if(value == date){
                        return true;
                     }else{
                        return false;
                     }
                  });
               }
            },
            moment: function (date) {
               return moment(date).format('MMMM DD, YYYY');
            },    
            openModal(data) {
               console.log(data);
               this.bookingDetails = data;
               this.showModal = true;
            },
            closeModal() {
                  this.showModal = null;
            },
            setShowDate(d) {
               this.showDate = d;
            },
            onClickEvent() { alert('event clicked'); },
            changeMonth(isNext) {
               let month = this.current.month;
               isNext === true ? (month = month + 1) : (month = month - 1);
               if (month <= 0) {
                  month = 12;
                  this.current.year = this.current.year - 1;
               }
               if (month > 12) {
                  month = 1;
                  this.current.year = this.current.year + 1;
               }
               this.current.month = month;
               this.current.date = 1;
            },
            getDateData(data) {
               if (data.none === true) {
                  return false;
               } else {
                  if (
                     data.years === this.current.year &&
                     data.month === this.current.month &&
                     data.date === this.current.date
                  ) {
                     return false;
                  } else {
                     this.current.year = data.years;
                     this.current.month = data.month;
                     this.current.date = data.date;
                     const selectedDate = data.years+'-'+data.month+'-'+data.date;
                     this.getApointments(selectedDate);

                  }
               }
            },
            getApointments(selectedDate){
               axios.post('/get-appointments', {date: selectedDate} )
                  .then(response => {
                     console.log(response, "response");
                     this.seletedDateAppointments = response.data.appointments;
                     // this.getData();
                     // this.msgData.text_msg = '';
                     // this.msgData.images = [];
                  })
                  .catch(error => {
                     console.log(error);
                  })
            },
            backToToday() {
               this.current.year = this.today.year;
               this.current.month = this.today.month;
               this.current.date = this.today.date;
            },
            getToday() {
               this.today.year = moment().year();
               this.today.month = moment().month() + 1;
               this.today.date = moment().date();
            },
            async getApointmentCount(){
               await axios.get('/get-appointments-count')
               .then(response => {
                  this.appointmentDates = response.data.appointments;
                  // this.getData();
                  // this.msgData.text_msg = '';
                  // this.msgData.images = [];
               })
               .catch(error => {
                  console.log(error);
               })
            },
            buildCalendar() {
               console.log(this.appointmentDates, "fdfdfdf");
               let myYears = this.current.year;
               let myMonth = this.current.month;
               let myDate = this.current.date;

               let monthText = "";
               myMonth < 10
                  ? (monthText = `0${myMonth}`)
                  : (monthText = myMonth.toString());

               let dateArray = [];
               let patchNum = 0;
               let totalDate = moment(`${myYears}-${monthText}`).daysInMonth();
               let week = moment(`${myYears}-${monthText}`).format("d");

               for (let i = 0; i < totalDate; i++) {
                  let dateNum = i + 1;
                  let isToday = false;
                  let isCurrent = false;
                  let dateText = "";

                  if (
                     myYears === this.today.year &&
                     myMonth === this.today.month &&
                     dateNum === this.today.date
                  ) {
                     isToday = true;
                  }

                  if (dateNum === myDate) {
                     isCurrent = true;
                  }

                  dateNum < 10
                     ? (dateText = `0${dateNum}`)
                     : (dateText = dateNum.toString());
                  let dateD  = myYears+'-'+myMonth+'-'+dateNum;
                  dateD = moment(dateD).format('YYYY-MM-DD');
                  let count = 0
                  if(this.appointmentDates){
                     Object.entries(this.appointmentDates).forEach(([key, value], index) => {
                        if(value == dateD){
                           count  = key;
                        }
                     });
                  }
                  let obj = {
                     years: myYears,
                     month: myMonth,
                     date: dateNum,
                     dateD:dateD,
                     count:count,
                     number: dateText,
                     today: isToday,
                     current: isCurrent,
                  };
                  dateArray.push(obj);
               }

               for (let i = 0; i < week; i++) {
                  let obj = {
                     number: "",
                     none: true
                  };
                  dateArray.splice(i, 0, obj);
               }

               dateArray.length % 7 === 0
                  ? (patchNum = 0)
                  : (patchNum = 7 - dateArray.length % 7);

               for (let i = 0; i < patchNum; i++) {
                  let obj = {
                     number: "",
                     none: true
                  };
                  dateArray.splice(dateArray.length, 0, obj);
               }
               return dateArray;
            },
            convertTwoDigits() {
               console.log(this.current.month, "this.current.month");
               return this.monthNames[this.current.month-1]+' ';
            }
        },
        computed: {
            
            
         },
         created() {
            this.getApointmentCount();
            this.getToday();
            this.backToToday();
         },
        mounted() { 
            
            console.log(this.buildCalendar, "dfsdfdsfs");
            //this.getApointmentCount();
            this.getApointments(this.showDate);
        }
    }
</script>