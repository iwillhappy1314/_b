<template>
  <section class="container">

    <h2>{{ title }}</h2>

    <el-card class="box-card">
      <div v-for="post in posts" :key="post.id">
        <div class="content">
          <p><strong>{{ post.title.rendered }}</strong></p>
          <div class="excerpt" v-if="post.excerpt.rendered" v-html="post.excerpt.rendered"></div>
        </div>
      </div>
    </el-card>

  </section>

</template>

<script>
import {Drag, Drop} from 'vue-drag-drop';

export default {
  name: 'Home',

  data() {
    return {
      title  : 'This is a sample posts list',
      loading: true,
      posts  : [],

      pageNumber: 1,

      // 分页
      current: 1,
      size   : 10,
      total  : 0,
    };
  },

  components: {
    Drag,
    Drop,
  },

  methods: {
    /**
     * 分页
     */
    makePagination(headers) {
      this.total = parseInt(headers['x-wp-total']);
    },

    getPosts() {
      this.loading = true;
      let endpoint = wenpriseSpaceNameFrontendSettings.root + 'posts?_embed&page=' + pageNumber;

      Vue.axios.get(endpoint).then(response => {
        this.current = pageNumber;

        response.data.forEach((post) => {
          this.posts.push(post);
        });

        this.loading = false;
        this.makePagination(response.headers);
      }).catch(e => {
        this.errors.push(e);
        this.loading = false;
      });
    },
  },
};
</script>

<style lang="scss">

._b {
  display: block;
}

</style>