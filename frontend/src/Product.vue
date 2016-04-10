<template>
  <div class="media">
    <div class="media-left">
      <a href="#">
        <img v-bind:src="model.photo" style="width:100px;" class="media-object"/>
      </a>
    </div>
    <div class="media-body">
      <h4 class="media-heading">{{model.name}}</h4>
      <h5 class="media-heading" style="padding-left:10px;">Цена: {{model.price}} р.</h5>
      <h5 class="media-heading"><b>Характеристики:</b></h5>
      <div style="padding-left:10px;">
        <div v-for="value in model.attributes">
          {{ $key }} : {{ value }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    model: {
      type: Object,
      required: true
    }
  }
}
</script>

<style>
</style>
