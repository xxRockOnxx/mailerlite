<template>
  <v-dialog
    v-model="open"
    max-width="384px"
  >
    <template v-slot:activator="{ attrs, on }">
      <slot
        name="activator"
        v-bind="{ attrs, on }"
      />
    </template>

    <v-card>
      <v-card-text class="pt-2">
        Are you sure you want to delete this subscriber?
      </v-card-text>

      <v-card-actions>
        <v-spacer />

        <v-btn
          text
          @click="open = false"
        >
          Cancel
        </v-btn>

        <v-btn
          text
          color="red"
          :loading="loading"
          @click="onSubmit"
        >
          Delete
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: {
    subscriber: {
      type: Object,
      required: true,
    }
  },

  data() {
    return {
      open: false,
      loading: false,
    }
  },

  methods: {
    onSubmit() {
      if (this.loading) {
        return
      }

      this.loading = true

      this.$subscribers
        .delete(this.subscriber.id)
        .then(() => {
          this.$emit('deleted')
          this.open = false
        })
        .finally(() => {
          this.loading = false
        })
    },
  }
}
</script>
