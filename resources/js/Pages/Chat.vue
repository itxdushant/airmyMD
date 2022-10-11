<template>
    <div class="main_body">
         <SideBar></SideBar>
         <!-- left_bar end here -->
         <div class="right_section">
            <div class="mobile_header d-block d-lg-none">
               <div class="mobile_flex">
                  <a href="#"><img src="/images/logo.png"></a>
                  <div class="toggle_icon">
                     <img class="toggle_menu" src="/images/toggle.png">
                  </div>
               </div>
            </div>
            <div class="my_appointments_right">
               <div class="top_bar_c">
                  <h3 class="h3_tittle">Messages</h3>
                  <div class="right_top_input">
                     <UserBar/>
                  </div>
               </div>

               <div class="chat_section" v-if="(conversion && conversion.length != 0) || keyChange">
                  <div class="row text-center" v-if="loading">
                     <div class="spinner-border" role="status" style="margin:50px auto;">
                     </div>
                  </div>

                  <div class="left_side">
                     <div class="serch_bar">
                        <input type="search" @keyup="keyupHandle($event.target.value)" placeholder="Search People"/>
                     </div>
                     <div class="" v-if="(conversion && conversion.length != 0) ">
                        <div v-for="(user,index) in conversion" :key="index" :class="(user.id == active)? 'persons_chats active': 'persons_chats'" >
                           <img :src="(user.profile_photo_url)? user.profile_photo_url :'/images/chat_1.png'" @click="selectUser(user.id)" class="pr_img"/>
                           <span v-if="user.latest_msg && user.latest_msg.is_read ==0 && user.latest_msg.to == this.user_id && is_read" class="is_read">{{user.unread}}</span>
                           <div class="name_t" @click="selectUser(user.id)">
                                 <h4 class="name_p" v-if="user.user_type == 'user'">{{ (user.patient_profile)? user.patient_profile.first_name+' '+user.patient_profile.last_name: '' }} </h4>
                                 <h4 class="name_p" v-else>{{ (user.profile)? user.profile.first_name+' '+user.profile.last_name: '' }} </h4>
                                 <p class="l_message" :id="user.id+'l_message'" v-if="user.latest_msg">{{user.latest_msg.message}}</p>
                           </div>
                        </div>
                     </div>
                     <div v-else class="alert alert-warning" >
                        <strong>No Data Found.</strong>
                     </div>

                  </div>
                  
                     <div class="right_side" v-if="allMsgs && active">
                        <div class="row text-center" v-if="loading2">
                           <div class="spinner-border" role="status" style="margin:50px auto;">
                           </div>
                        </div>
                        <div class="body_msg" id="body_msg" v-on:scroll.passive="handleScroll($event)">
                           <div id="scoller_"></div>
                           <div class="right_side_msg" v-for="(msgData,index) in allMsgs" :key="index">
                              <span class="text-center date_show">{{ moment(index)}}</span>
                              <div class="right_side_msg" v-for="(msg,index) in msgData" :key="index">
                                 <div class="reciver_msg" v-if="msg.to == user_id">
                                    <div class="common_s">
                                       <div class="img-ouoter" v-if="msg.media">
                                          <div class="msg-img" v-for="(media,index) in msg.media" :key="index">
                                             <img :src="media"/>
                                          </div>
                                       </div>
                                       <p class="msg_s">{{msg.message}}</p>
                                       <span class="time_1">{{getTime(msg.created_at)}}</span>
                                    </div>
                                 </div>

                                 <div class="sender_msg" v-else>
                                    <div class="common_s">
                                       <div class="img-ouoter" v-if="msg.media">
                                          <div class="msg-img" v-for="(media,index) in msg.media" :key="index">
                                             <img :src="media"/>
                                          </div>
                                       </div>
                                       <p class="msg_s" v-if="msg.message">{{msg.message}}</p>
                                       <span class="time_1">{{getTime(msg.created_at)}}</span>
                                       </div>
                                 </div>
                              </div>

                           </div>  
                          <div id="msg-scroll"></div> 
                        </div>
                        <div class="message_sender" >
                              <div v-if="msgData.images" class="sent_img">
                                 <div v-for="(image, index) in msgData.images" :key="index" class="sent_img_data">
                                    <img :src="image" />
                                    <button @click="removeImage(index)">Remove image</button>
                                 </div>
                              </div>
                              <!-- <input type="text"
                                    v-model="msgData.text_msg"
                                    v-on:keyup.enter="onEnter"
                              /> -->
                              <input type="text"
                                    v-on:keyup="messageHandle($event.target.value)"
                                    v-on:keyup.enter="onEnter"
                                    ref="message"
                              />
                              <div class="label_paperclip">
                                 <label for="msg_file">
                                    <img src="/images/Paperclip.png"/>
                                 </label>
                                 <input type="file" id="msg_file" @change="onFileChange" ref="file" accept="image/png, image/gif, image/jpeg" multiple/>
                              </div>
                              <button class="btn_send"  @click="sendMessage">Send</button>
                           </div>
                     </div>
                     <div v-else class="right_chat_blnk">
                        <div >
                         <div class="active_prson">
                          <img :src="doctor.profile_photo_url"/>
                          <div class="w_name">
                           <h3>Welcome</h3>
                           <p>
                              {{(doctor.profile.first_name)? doctor.profile.first_name: ''}}
                              {{(doctor.profile.last_name)? doctor.profile.last_name: ''}}
                           </p>
                          </div> 
                         </div>
                         <div class="blnk_img_chat">
                              <img src="/images/imgpsh_fullsize_anim.png"/>
                         </div>
                        </div>
                     </div>

               </div>
               <div v-else class="alert alert-warning" >
                  <strong>No Data Found.</strong>
               </div>
               <div class="pagination_div">
                  
               </div>
            </div>
            
         </div>
         <!-- right_section end here -->
      </div>
</template>
<style scoped>
   #scoller_{
      margin-top: 10px;
   }
</style>
<script>
   import SideBar from '@/Components/SideBar'
   import UserBar from '@/Components/UserBar'
   import LaravelVuePagination from 'laravel-vue-pagination';
   import moment from 'moment'
   export default {
      components: {
         SideBar,
         UserBar,
         'Pagination': LaravelVuePagination
      },
      props: ['conversion', 'user_id', 'selected_id', 'doctor'],
      data() {
         return {
            conversion: this.conversion,
            newMessage: "",
            allMsgs:null,
            loading: false, 
            loading2: false,
            height: 0,
            is_read: true,
            keyChange: false,
            msgData: {
               text_msg: null,
               images: [],
               user_id: null
            },
            active: "",
            index: 0,
            page: 0,
         };
      },
      methods: {  
         handleScroll: function(e){
            let height = document.getElementById('body_msg').scrollTop;
            console.log(height);
            if(height == 0 && this.active != ''){
               this.loading2 = true;
               axios.post('/get-messages', {user_id: this.msgData.user_id, page: this.page } )
               .then(response => {
                  console.log(response.data, "Sdfsdfdsfdsfsdfs");
                  //this.allMsgs = response.data;
                  this.loading2 = false;
                  if(response.data.length === undefined ){
                     this.allMsgs = Object.assign(response.data,this.allMsgs);
                     console.log(this.allMsgs);
                     this.page = this.page+1;
                     setTimeout(function(){
                        var myElement = document.getElementById('scoller_');
                        var topPos = myElement.offsetTop;
                        // var myElement = document.querySelector('.right_side_msg');
                        // var topPos = myElement.offsetTop;
                        document.getElementById('body_msg').scrollTop = topPos;
                     }, 100);
                  }
               })
               .catch(error => {
                  this.loading2 = false;
                  console.log(error);
               })
            }
            //console.log(height, "pppp");
         },
         moment: function (date) {
            const yesterday = moment(date).subtract(1, "days").format("MMMM DD, YYYY");
            if(moment().isSame(yesterday, 'day')){
               return "Yesterday";
            }
            if(moment().isSame(date, 'day')){
               return "Today";
            }
            return moment(date).format('MMMM DD, YYYY');
         },
         getTime(myDate){
            return moment(myDate).format('hh:mm A');
         },
         messageHandle(e){
            this.msgData.text_msg = e;
         },
         keyupHandle(e){
            this.keyChange = true;
               axios.post('/search-patient', {keyword: e } )
               .then(response => {
                  console.log(response, "This is response ");
                  this.conversion = response.data.conversion;
               })
               .catch(error => {
                  console.log(error);
               })
         },
         onEnter: function() {
            // this.$refs["message"].value = "";
            this.sendMessage();
            console.log('on enter event', this.msgData);
         },
         onFileChange(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length) return;
            this.createImage(files);
         },
         createImage(files) {
            var vm = this;
            for (var index = 0; index < files.length; index++) {
               var reader = new FileReader();
               reader.onload = function(event) {
                  const imageUrl = event.target.result;
                  vm.msgData.images.push(imageUrl);
               }
               reader.readAsDataURL(files[index]);
            }
         },
         removeImage(index) {
            this.msgData.images.splice(index, 1)
         },
         selectUser(user_id, page=0){
            this.is_read = false;
            this.active = user_id;
            this.msgData.user_id = user_id;
            axios.post('/get-messages', {user_id: this.msgData.user_id, page:page } )
            .then(response => {
               this.allMsgs = response.data;
               this.page = page+2;
               setTimeout(function(){
                  var myElement = document.getElementById('msg-scroll');
                  var topPos = myElement.offsetTop;
                  document.getElementById('body_msg').scrollTop = topPos;
                  window.scrollTo(0, document.body.scrollHeight);
               }, 100);
            })
            .catch(error => {
               console.log(error);
            })
         },
         uploadFile() {
            this.msgData.file_msg = this.$refs.file.files;
         },
         sendMessage(){
               this.$refs["message"].value = "";
               console.log(this.msgData.images, "testing");
               if(this.msgData.text_msg || this.msgData.images.length > 0){
                  axios.post('/sent-message', this.msgData )
                  .then(response => {
                     let myElement = document.getElementById(this.active+'l_message');
                     myElement.innerHTML = this.msgData.text_msg;
                     this.msgData.text_msg = null;
                     this.msgData.images = [];
                     setTimeout(function(){ 
                        var myElement = document.getElementById('msg-scroll');
                        var topPos = myElement.offsetTop;
                        document.getElementById('body_msg').scrollTop = topPos;
                     });
                  })
                  .catch(error => {
                     console.log(error);
                  })
               }


         },
      },
      mounted() { 
         console.log(this.conversion, "dfgdfgd");
         if(this.selected_id != 0){
            this.selectUser(this.selected_id);
         }

         Echo.private('chat')
         .listen('MessageSent', (e) => {
            if(e.message){
               if(e.recentMsg){
                  console.log(e, "dsfsdfsdfsdfsd");
                  let myElement = document.getElementById(e.user+'l_message');
                  myElement.innerHTML = e.recentMsg;
               }
               this.allMsgs = e.message.message;
            }
            
         });
      }
    }
</script>