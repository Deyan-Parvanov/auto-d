<template>
  <div>
    <span class="font-bold">{{ listing.engine_type }}</span> engine <span class="text-gray-400">| </span>
    <span class="font-bold">{{ listing.horsepower }}</span> Hp <span class="text-gray-400">| </span>
    <span class="font-bold">{{ listing.city }}</span>
  </div>
</template>

<script>
export default {
  props: {
    listing: {
      type: Object,
      required: true,
    },
  },
};
</script>