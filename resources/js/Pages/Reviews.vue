<template>
<Head title="Profile" />
    <div class="main_body">
        <SideBar/>
        <!-- left_bar end here -->
        
        <div class="card_review_section" v-if="!loading">
            <div class="top_bar_c pb-50">
                <h3 class="h3_tittle ">Reviews</h3>
                <div class="right_top_input">
                    <!-- <label for="search">
                        <input type="search" name="search">
                        <img src="images/search_1.png">
                    </label> -->
                    <UserBar/>
                </div>
            </div>
            <div class="review-outer" v-if="reviews">
                <div class="card_review" v-for="(review,index) in reviews.data" :key="index">
                    <div class="img_text_name">
                        <div class="left_img_rt">
                            <img :src="(review.patient_profile)? review.patient_profile.profile_photo_url: 'https://images.ctfassets.net/hrltx12pl8hq/7JnR6tVVwDyUM8Cbci3GtJ/bf74366cff2ba271471725d0b0ef418c/shutterstock_376532611-og.jpg'">
                            <h4 class="name_reviewer">{{(review.patient_profile.patient_profile.first_name)? review.patient_profile.patient_profile.first_name:''}} {{(review.patient_profile.patient_profile.last_name)? ' '+review.patient_profile.patient_profile.last_name:''}}</h4>
                            <p class="p_msg">
                                {{(review.review)? review.review:'Review not given.'}}
                                
                            </p>
                        </div>
                        
                        <div class="right_star_rt">
                            <span class="date_review">{{moment(review.created_at)}}</span>
                            <fieldset class="rate">
                                <input type="radio" :name="review.id" value="10" disabled :checked="(review.rating == 5)? true: false"/><label  title="5 stars"></label>
                                <input type="radio" :name="review.id" value="9" disabled :checked="(review.rating == 4.5)? true: false"/><label class="half"  title="4 1/2 stars"></label>
                                <input type="radio" :name="review.id" value="8" disabled :checked="(review.rating == 4)? true: false"/><label  title="4 stars"></label>
                                <input type="radio" :name="review.id" value="7" disabled :checked="(review.rating == 3.5)? true: false"/><label class="half"  title="3 1/2 stars"></label>
                                <input type="radio" :name="review.id" value="6" disabled :checked="(review.rating == 3)? true: false"/><label  title="3 stars"></label>
                                <input type="radio" :name="review.id" value="5" disabled :checked="(review.rating == 2.5)? true: false"/><label class="half"  title="2 1/2 stars"></label>
                                <input type="radio" :name="review.id" value="4" disabled :checked="(review.rating == 2)? true: false"/><label  title="2 stars"></label>
                                <input type="radio" :name="review.id" value="3" disabled :checked="(review.rating == 1.5)? true: false"/><label class="half"  title="1 1/2 stars"></label>
                                <input type="radio" :name="review.id" value="2" disabled :checked="(review.rating == 1)? true: false"/><label  title="1 star"></label>
                                <input type="radio" :name="review.id" value="1" disabled :checked="(review.rating == 0.5)? true: false"/><label class="half" title="1/2 star"></label>
                            </fieldset>
                        </div>                
                    </div>
                    
                </div>
                
                <!-- <div class="card_review">
                    <div class="img_text_name">

                <div class="left_img_rt">
                    <img src="https://images.ctfassets.net/hrltx12pl8hq/7JnR6tVVwDyUM8Cbci3GtJ/bf74366cff2ba271471725d0b0ef418c/shutterstock_376532611-og.jpg">
                    <h3 class="name_reviewer">Nina Hooly</h3>
                <span class="date_review">29 aug 2022</span>
                </div>

                <div class="right_star_rt">
                    <img src="https://cdn.pixabay.com/photo/2021/10/11/00/58/star-6699069__340.png">
                    <img src="https://cdn.pixabay.com/photo/2021/10/11/00/58/star-6699069__340.png">
                    <img src="https://cdn.pixabay.com/photo/2021/10/11/00/58/star-6699069__340.png">
                    <img src="https://cdn.pixabay.com/photo/2021/10/11/00/58/star-6699069__340.png">
                    <img src="https://cdn.pixabay.com/photo/2021/10/11/00/58/star-6699069__340.png">
                </div>
                    
                    </div>

                    <p class="p_msg">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer volutpat ullamcorper lorem. Nullam eleifend est quis ante malesuada, quis venenatis lacus laoreet
                    </p>
                </div>
                <div class="card_review">
                    <div class="img_text_name">
                        <div class="left_img_rt">
                            <img src="https://images.ctfassets.net/hrltx12pl8hq/7JnR6tVVwDyUM8Cbci3GtJ/bf74366cff2ba271471725d0b0ef418c/shutterstock_376532611-og.jpg">
                            <h3 class="name_reviewer">Nina Hooly</h3>
                            <span class="date_review">29 aug 2022</span>
                        </div>
                        <div class="right_star_rt">
                            <img src="https://cdn.pixabay.com/photo/2021/10/11/00/58/star-6699069__340.png">
                            <img src="https://cdn.pixabay.com/photo/2021/10/11/00/58/star-6699069__340.png">
                            <img src="https://cdn.pixabay.com/photo/2021/10/11/00/58/star-6699069__340.png">
                            <img src="https://cdn.pixabay.com/photo/2021/10/11/00/58/star-6699069__340.png">
                            <img src="https://cdn.pixabay.com/photo/2021/10/11/00/58/star-6699069__340.png">
                        </div>
                    </div>

                    <p class="p_msg">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer volutpat ullamcorper lorem. Nullam eleifend est quis ante malesuada, quis venenatis lacus laoreet
                    </p>
                </div> -->
            </div>
            
            <div class="review-outer" v-else>
                <p>Review are not Found.</p>
            </div>
        </div>
        <div class="row text-center card_review_section" v-if="loading">
            <div class="spinner-border" role="status" style="margin:100px auto;">
            </div>
        </div>
        <!-- right_section end here -->
        
    </div>
    <div class="pagination_div">
        <Pagination :data="reviews" @pagination-change-page="list" />
    </div>
    
    
    </template>
    <style scoped>
        /* Base setup */
        @import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);
        .review-outer {
            margin-top: 20px;
        }
    </style>
<script>
    import SideBar from '@/Components/SideBar'
    import UserBar from '@/Components/UserBar'
    import moment from 'moment'
    import LaravelVuePagination from 'laravel-vue-pagination';
    export default {
        components: {
            SideBar,
            UserBar,
            'Pagination': LaravelVuePagination
        },
        props: ['reviews'],
        data() {
            return { 
                loading: false,
                reviews: this.reviews
            }
        },
        methods: {   
            moment: function (date) {
                return moment(date).format('MMMM DD, YYYY');
            },
            list(page = 1) {
                console.log(page);
                this.page = page;
                this.getData();
            },
            getData() {
                this.loading = true;
                axios.get(`/reviews-list?page=${this.page}`).then(({data}) => {
                    console.error(data)                     
                    this.reviews = data;
                    this.loading = false;
                }).catch(({ response })=>{
                    this.loading = false;
                    console.error(response)
                })
            },
        },
        mounted() { 
            console.log(this.reviews);  
        }
    }
</script>