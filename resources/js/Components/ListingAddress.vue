<template>
  <router-link :to="{ name: 'listingShow', params: { id } }">
    <span>
      {{ listing.model }} {{ listing.make }}, {{
        listing.category }}, for ${{ listing.price }}
    </span>
  </router-link>

</template>

<script>
export default {
  props: {
    listing: {
      type: Object,
      required: true,
    },
    id: {
      required: true,
    }
  },
};
</script>