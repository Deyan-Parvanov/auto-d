<template>
    <span>{{ formattedPrice }}</span>
</template>

<script>
export default {
  props: {
    price: {
      type: [Number, String],
    },
  },
  computed: {
    formattedPrice() {
        return Number(this.price).toLocaleString('en-US', {
            style: 'currency',
            currency: 'USD',
            maximumFractionDigits: 0,
        })
    },
  }
};
</script>