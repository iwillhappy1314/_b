<template>
  <section class="container">
    <h1>Packages</h1>

    <el-data-table v-bind="$data"></el-data-table>
  </section>

</template>

<script>
import Qs from 'qs';

export default {
  name: 'PackageAdmin',

  data() {
    return {
      axiosConfig: {
        transformRequest: [
          function(data) {
            return Qs.stringify(data);
          }],
        headers         : {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-WP-Nonce'  : wenpriseSpaceNameAdminSettings.nonce,
        },
      },
      url        : wenpriseSpaceNameAdminSettings.root + 'wp-transship/v1/addresses',
      columns    : [
        {
          prop : 'name',
          label: '用户名',
        },
      ],
      searchForm : [
        {
          type : 'input',
          id   : 'name',
          label: '用户名',
          el   : {
            placeholder: '请输入',
          },
        },
      ],
      form       : [
        {
          type : 'input',
          id   : 'name',
          label: '用户名',
          el   : {
            placeholder: '请输入',
          },
          rules: [
            {
              required: true,
              message : '请输入用户名',
              trigger : 'blur',
            },
          ],
        },
        {
          type : 'input',
          id   : 'name',
          label: 'Display Name',
          el   : {
            placeholder: '请输入',
          },
          rules: [
            {
              required: true,
              message : '请输入用户名',
              trigger : 'blur',
            },
          ],
        },
      ],
      hasView: true
    };
  },

  created: function() {
    // `this` 指向 vm 实例
    console.log('a is: ' + this.a);
  },

  methods: {
    // 拖放图片后的操作
    onSubmit(data) {

    },
    onClickLeft() {
      this.$router.push('/');
    },
    onClickRight() {
      this.$router.push('/enrollment');
    },
    onLoad() {
      // 异步更新数据
      // setTimeout 仅做示例，真实场景中一般为 ajax 请求
      setTimeout(() => {
        for (let i = 0; i < 10; i++) {
          this.list.push(this.list.length + 1);
        }

        // 加载状态结束
        this.loading = false;

        // 数据全部加载完成
        if (this.list.length >= 40) {
          this.finished = true;
        }
      }, 1000);
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss">

.wp-transship {
  display: block;
}

</style>