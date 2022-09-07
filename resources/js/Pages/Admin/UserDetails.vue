<template>
    <div class="main_body">
        <AdminSideBar></AdminSideBar>
        <div class="right_section">
            <div class="accordon"
                v-for="item in items"
                :key="item.id">
                <div class="card-header"
                    @click.prevent="toggleExpand(item)">
                <span>{{ item.title }}</span>
                <div class="icon">
                    <i class="fas fa-arrow-down" v-if="!item.isExpand"></i>
                    <i class="fas fa-arrow-up" v-if="item.isExpand"></i>
                </div>
                </div>
                
                <div class="card-body"
                    :ref="'content' + item.id"
                    :style="[item.isExpand ? {height: item.computedHeight} : {}]">
                <hr />
                <div class="card-content">{{ item.content }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>

   .accordon{
  width: 80%;
  height: auto;
  display: block;
  position: relative;
  margin: 8px 0;
  padding: 0 10px;
  border: 1px solid #aaa;
  border-radius: 4px;
}

.card-header,
.card-content {
  margin: 10px 0;
}

.card-header {
  cursor: pointer;
}

.card-header span {
  font-weight: 600;
}

.card-body {
  height: 0;
  overflow: hidden;
  transition: 0.3s;
}

.icon {
  float: right;
}

hr {
  margin: 0;
  height: 1px;
  display: block;
  border-width: 0;
  border-top: 1px solid #aaa;
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
      props: ['doctor'],
      data() {
        return {
        items: [
            {
            id: 1,
            title: "Sample Title 1",
            content: "Lorem ipsum dolor sit amet",
            isExpand: true,
            computedHeight: 0,
            },
            {
            id: 2,
            title: "Sample Title 2",
            content: "Felis eget nunc lobortis mattis aliquam faucibus purus in massa. Maecenas volutpat blandit aliquam etiam.",
            isExpand: false,
            computedHeight: 0,
            },
            {
            id: 3,
            title: "Sample Title 3",
            content: "Pretium quam vulputate dignissim suspendisse. Tristique senectus et netus et. Vulputate ut pharetra sit amet aliquam. Leo urna molestie at elementum eu facilisis sed odio morbi. Ut morbi tincidunt augue interdum velit euismod in pellentesque massa.",
            isExpand: false,
            computedHeight: 0,
            },
            {
            id: 4,
            title: "Sample Title 4",
            content: "Odio euismod lacinia at quis risus sed vulputate. Nulla pharetra diam sit amet nisl suscipit adipiscing bibendum est. Proin sagittis nisl rhoncus mattis rhoncus urna neque viverra justo. Ut pharetra sit amet aliquam id diam maecenas. Enim ut tellus elementum sagittis vitae et leo. Egestas fringilla phasellus faucibus scelerisque eleifend. Odio euismod lacinia at quis risus sed vulputate odio.",
            isExpand: false,
            computedHeight: 0,
            },
        ]
        }
    },
    methods: {
        toggleExpand(item) {
        item.isExpand = !item.isExpand;
        },
        getComputedHeight() {
        this.items.forEach(item => {
            var content = this.$refs["content" + item.id][0];
            
            content.style.height = 'auto';
            content.style.position = 'absolute';
            content.style.visibility = 'hidden';
            content.style.display = 'block';

            var height = getComputedStyle(content).height;
            item.computedHeight = height;

            content.style.height = 0;
            content.style.position = null;
            content.style.visibility = null;
            content.style.display = null;
        });
        }
    },
    mounted() {
        this.getComputedHeight();
    },
    }
</script>