<template>
  <div class="py-5">
    <div class="d-flex">
      <div class="text-h6">Subscribers</div>

      <dialog-subscriber @saved="onCreateSubscriber">
        <template v-slot:activator="{ attrs, on }">
          <v-btn
            class="ml-auto"
            color="primary"
            v-bind="attrs"
            v-on="on"
          >
            Create Subscriber
          </v-btn>
        </template>
      </dialog-subscriber>
    </div>

    <v-data-table
      class="mt-5"
      :headers="tableHeaders"
      :items="subscribers"
    >
      <template v-slot:item.actions="{ item }">
        <dialog-fields
          :subscriber="item"
        >
          <template v-slot:activator="{ attrs, on }">
            <v-btn
              icon
              v-bind="attrs"
              v-on="on"
            >
              <v-icon>mdi-eye</v-icon>
            </v-btn>
          </template>
        </dialog-fields>

        <dialog-subscriber
          :existing="true"
          :subscriber="item"
          @saved="onUpdateSubscriber"
        >
          <template v-slot:activator="{ attrs, on }">
            <v-btn
              icon
              v-bind="attrs"
              v-on="on"
            >
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
          </template>
        </dialog-subscriber>

        <dialog-delete
          :subscriber="item"
          @deleted="onDeleteSubscriber(item)"
        >
          <template v-slot:activator="{ attrs, on }">
            <v-btn
              icon
              v-bind="attrs"
              v-on="on"
            >
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </template>
        </dialog-delete>
      </template>
    </v-data-table>
  </div>
</template>

<script>
export default {
  name: 'IndexPage',

  data() {
    return {
      subscribers: [],
    }
  },

  computed: {
    tableHeaders() {
      return [
        {
          text: 'Name',
          value: 'name',
        },
        {
          text: 'Email',
          value: 'email',
        },
        {
          text: 'State',
          value: 'state',
        },
        {
          text: 'Actions',
          value: 'actions',
          sortable: true,
        },
      ]
    }
  },

  mounted() {
    this.$subscribers
      .list()
      .then(subscribers => {
        this.subscribers = subscribers
      })
  },

  methods: {
    onCreateSubscriber(subscriber) {
      this.subscribers.push(subscriber)
    },

    onUpdateSubscriber(subscriber) {
      const index = this.subscribers.findIndex(item => item.id === subscriber.id)

      if (index === -1) {
        return
      }

      Object.assign(this.subscribers[index], subscriber)
    },

    onDeleteSubscriber(deleted) {
      const index = this.subscribers.findIndex((s) => s.id === deleted.id)

      if (index !== -1) {
        this.subscribers.splice(index, 1)
      }
    }
  }
}
</script>
