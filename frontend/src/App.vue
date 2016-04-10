<template>
  <div id="app" class="container theme-showcase" style="margin-top:15px;">
    <div class="well well-sm">

      <!-- Фильтры -->

      <group-filters v-for="groupFilters in groupsFilters" :name="groupFilters.name" :filters="groupFilters.filters"></group-filters>

      <!-- Поиск -->
      <div class="row">
        <div class="col-md-12">
          <button v-on:click="loadAll" type="button" class="btn btn-sm btn-default pull-right">Сброс</button>
          <button v-on:click="filtration" type="button" class="btn btn-sm btn-default pull-right" style="margin-right: 10px;">Поиск</button>
        </div>
      </div>

    </div>

    <!-- Продукты -->
    <div class="row">
      <div class="col-md-4" v-for="product in products" style="padding:10px;">
        <product :model="product"></product>
      </div>
    </div>

  </div>
</template>

<script>


import GroupFilters from './GroupFilters.vue'
import Product from './Product.vue'



var getAll = function (vm) {
    return vm.$http.get('http://localhost/FilterForGalament/app/web.php', {action: 'getAll'});
}

export default {
  components: {GroupFilters, Product},
  ready: function () {
    this.loadAll();
  },
  data () {
    return {
      groupsFilters: [],
      products: []
    }
  },
  http: {
      root: 'http://localhost/FilterForGalament/app/web.php',
  },
  methods: {
    loadAll: function () {
      this.$http.get('', {action: 'getAll'}).then((response) => {
        this.products = response.data.products;
        this.groupsFilters = response.data.groupsFilters;
      });
    },
    filtration: function () {
      var filters = [];

      var length = this.groupsFilters.length;
      for (var i = 0; i < length; i++) {
        this.groupsFilters[i].filters.forEach((filter) => {
          if (filter.active) {
            filters.push(filter);
          }
        });
      }

      this.$http.post(
        '','',
        { data: JSON.stringify(filters), params: {action: 'filtration'} }
      ).then((response) => {
        this.products = response.data.products;
        this.groupsFilters = response.data.groupsFilters;
      });
    }
  }
}
</script>

<style>
body {
  font-family: Helvetica, sans-serif;
}
</style>
