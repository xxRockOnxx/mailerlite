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
        <div class="text-h6">Subscriber Fields</div>
      </v-card-title>

      <v-card-text
        v-if="loading"
        class="text-center"
      >
        <v-progress-linear
          color="primary"
          indeterminate
        />
      </v-card-text>

      <v-card-text v-else>
        <v-row
          v-for="(field, index) in fields"
          :key="index"
        >
          <v-col cols="11">
            <v-row>
              <v-col col="6">
                <v-text-field
                  v-model="fields[index].title"
                  label="Title"
                  :error="errors.hasOwnProperty('title')"
                  :error-messages="errors.title"
                />
              </v-col>
              <v-col col="6">
                <v-select
                  v-model="fields[index].type"
                  label="Type"
                  :items="validTypes"
                  :error="errors.hasOwnProperty('type')"
                  :error-messages="errors.type"
                />
              </v-col>
            </v-row>
          </v-col>

          <v-col cols="1">
            <v-btn
              class="mt-4"
              icon
              @click="onRemoveField(index)"
            >
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </v-col>
        </v-row>

        <v-btn
          class="mt-2"
          text
          block
          @click="onAddField"
        >
          <v-icon left>mdi-plus</v-icon>
          Add new field
        </v-btn>
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
          :loading="saving"
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
    subscriber: {
      required: true,
      type: Object,
    }
  },

  data() {
    return {
      open: false,
      loading: false,
      saving: false,
      fields: [],
      errors: {},
    }
  },

  watch: {
    open(value) {
      if (value) {
        this.fields = []
        this.getFields()
      }
    }
  },

  computed: {
    validTypes() {
      return [
        'date',
        'number',
        'string',
        'boolean',
      ]
    }
  },

  methods: {
    getFields() {
      if (this.loading) {
        return
      }

      this.loading = true

      return this.$fields
        .list(this.subscriber.id)
        .then((fields) => {
          this.fields = fields
        })
        .finally(() => {
          this.loading = false
        })
    },

    onAddField() {
      this.fields.push({
        title: '',
        type: this.validTypes[0]
      })
    },

    onRemoveField(index) {
      this.fields.splice(index, 1)
    },

    onSubmit() {
      if (this.saving) {
        return
      }

      this.saving = true

      this.$fields
        .set(this.subscriber.id, this.fields)
        .then((fields) => {
          this.$emit('saved', fields)
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
          this.saving = false
        })
    }
  }
}
</script>
