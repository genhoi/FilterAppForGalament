<template>
  <span>
    <input type="checkbox"  v-model="model.active" v-bind:disabled="disabled">
    <span  v-bind:class="{ 'text-muted': disabled }">  {{ model.value }}</span>
    <span class="badge" v-show="model.count >= 0">{{model.count}}</span>
  </span>
</template>

<script>
export default {
  props: {
    model: {
      type: Object,
      required: true
    }
  },
  computed: {
    disabled: function () {
      if (this.model.count !== undefined && this.model.count == 0) {
        return true;
      }
      return false;
    }
  }
}
</script>

<style>
</style>
