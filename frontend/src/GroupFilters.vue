<template>
  <div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <b>{{name}}</b>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-2 col-md-3 col-sm-4" v-for="filter in filters">
        <filter :model="filter"></filter>
      </div>
    </div>
  </div>

</template>

<script>
import Filter from './Filter.vue'

export default {
  components: {Filter},
  props: {
    name: {
      type: String,
      required: true
    },
    filters: {
      type: Array,
      required: true
    }
  }
}
</script>

<style>
</style>
