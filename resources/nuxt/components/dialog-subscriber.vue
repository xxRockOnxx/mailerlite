<template>
  <v-dialog
    v-model="open"
    max-width="512px"
  >
    <template v-slot:activator="{ attrs, on }">
      <slot
        name="activator"
        v-bind="{ attrs, on }"
      />
    </template>

    <v-card
      tag="form"
      @submit.prevent="onSubmit"
    >
      <v-card-title>
        <span class="headline">
          {{ existing ? 'Update' : 'Create' }} Subscriber
        </span>
      </v-card-title>

      <v-card-text>
        <v-text-field
          v-model="name"
          label="Name"
          :error="errors.hasOwnProperty('name')"
          :error-messages="errors.name"
        />

        <v-text-field
          class="mt-2"
          v-model="email"
          label="Email"
          :error="errors.hasOwnProperty('email')"
          :error-messages="errors.email"
        />

        <v-radio-group v-model="state">
          <v-radio
            v-for="state in validStates"
            :key="state"
            :value="state"
            :label="state"
          />
        </v-radio-group>
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
          type="submit"
          color="primary"
          :loading="loading"
        >
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: {
    existing: {
      type: Boolean,
      default: false,
    },

    subscriber: {
      type: Object,
      default: () => ({
        name: '',
        email: '',
        state: 'active',
      }),
    }
  },

  data() {
    return {
      open: false,

      name: this.subscriber.name,
      email: this.subscriber.email,
      state: this.subscriber.state,

      loading: false,

      errors: {},
    }
  },

  watch: {
    open(value) {
      if (value) {
        this.name = this.subscriber.name
        this.email = this.subscriber.email
        this.state = this.subscriber.state
        this.errors = {}
      }
    }
  },

  computed: {
    validStates() {
      return [
        'active',
        'unsubscribed',
        'junk',
        'bounced',
        'unconfirmed',
      ]
    }
  },

  methods: {
    onSubmit() {
      if (this.loading) {
        return
      }

      this.loading = true

      const action = this.existing
        ? this.update
        : this.create

      action()
        .then((subscriber) => {
          this.$emit('saved', subscriber)
          this.open = false
        })
        .catch((err) => {
          if (!err.response) {
            throw err
          }

          if (err.response.status === 422) {
            this.errors = err.response.data.errors
            return
          }

          throw err
        })
        .finally(() => {
          this.loading = false
        })
    },

    create() {
      return this.$subscribers.create({
        name: this.name,
        email: this.email,
        state: this.state,
      })
    },

    update() {
      return this.$subscribers.update(this.subscriber.id, {
        name: this.name,
        email: this.email,
        state: this.state,
      })
    },
  }
}
</script>
