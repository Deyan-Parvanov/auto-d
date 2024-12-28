<template>
<div class="flex gap-1">
    <a
        v-for="(link, index) in links"
        :key="index"
        class="py-2 px-4 rounded-md"
        :href="link.url"
        :class="{'bg-indigo-500 dark:bg-indigo-800 text-gray-300': link.active}"
        v-html="link.label"
        @click.prevent="handlePageChange(link)"
    />
</div>
</template>

<script>
export default {
  name: 'Pagination',
  props: {
    links: {
      type: Array,
      required: true,
    },
  },
  methods: {
    handlePageChange(link) {
      if (!link.url) return;
      const page = new URL(link.url).searchParams.get("page");
      this.$emit('page-changed', { page });
    },
  },
};
</script>