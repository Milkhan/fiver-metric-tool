<template>
  <div class="fiver-metric-class">
    <div :class="{ dark: dark }">

      <Head title="Fiver Metric Tool" />

      <!-- Title for the tool on the top  -->
      <Heading class="mb-6">Fiver Metric Tool</Heading>
      <div class="w-96  	self-center	mx-auto mb-4">
        <!-- First select to search/select events  -->
        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Event</label>
        <v-select label="event_name" :options="events" :value="event_id" @change="getEntrees" v-model="selectedEvent"
          :searchable="true" :clearable="true" :close-on-select="true" :placeholder="'Select Event'"></v-select>
      </div>

      <Preloader color="gray" scale="0.6" v-if="isLoading" />

      <!-- 3 metrics for selected event -->
      <div class="grid md:grid-cols-12 gap-6 w-full" v-if="resourceId">

        <ValueMetric :name="name3" :card="_card3" :prefixComponent=false :onlyOnDetail=true :class="_class"
          :title="name3" :resourceName="resourceName" :resourceId="resourceId" :uriKey="uriKey" :helpWidth=250
          :helpText="helpText3" :refreshWhenActionRuns=false :refreshWhenFiltersChange=false v-if="resourceId" />

        <PartitionMetric :name="name2" :card="_card2" :prefixComponent=false :onlyOnDetail=true :class="_class"
          :title="name2" :resourceName="resourceName" :resourceId="resourceId" :uriKey="uriKey2" :helpWidth=250
          :helpText="helpText2" :refreshWhenActionRuns=false :refreshWhenFiltersChange=false v-if="resourceId" />

        <ProgressMetric :name="name" :card="_card" :prefixComponent=false :onlyOnDetail=true :class="_class"
          :title="name" :resourceName="resourceName" :resourceId="resourceId" :uriKey="uriKey" :helpWidth=250
          :helpText="helpText1" :refreshWhenActionRuns=false :refreshWhenFiltersChange=false v-if="resourceId" />
      </div>

      <div class="w-96  self-center	mx-auto my-4" v-if="resourceId">
        <!-- Entree select to search/select entrees with event_id of the selected event  -->
        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
          Entree</label>
        <select id="classrooms" v-model="selectedEntree" @change="setEntree($event)"
          class="bg-slate-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 w-48 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
          <option disabled value="">Plese select an Entree </option>
          <option v-for="(option, index) in entrees" v-bind:value="index">{{ option.entree_title }}</option>
        </select>
      </div>

      <div class="grid md:grid-cols-12 gap-6 w-full" v-if="resourceIdForEntree">
        <ProgressMetric :name="name7" :card="_card7" :prefixComponent=false :onlyOnDetail=true :class="_class"
          :title="name7" :resourceName="resourceName2" :resourceId="resourceIdForEntree" :uriKey="uriKey" :helpWidth=250
          :helpText="helpText7" :refreshWhenActionRuns=false :refreshWhenFiltersChange=false
          v-if="resourceIdForEntree && entreeStarted != 1" />
        <!-- Optional metric according to code below
        $entreeStarted = Entree::where('entree_startdate', '>=', Carbon::now())
            ->where('entree_id', $request->resourceId)
            ->count();

        if ($entreeStarted === 1) {
            return [
                (new EntreeCapacity)->help('Percentage van hoever de entree is uitverkocht.')->width('1/3')->onlyOnDetail(),
                (new TicketsBought)->width('1/3')->onlyOnDetail(),
                (new EntreeRevenue)->width('1/3')->onlyOnDetail(),
            ];
        }
        -->
        <ValueMetric :name="name5" :card="_card5" :prefixComponent=false :onlyOnDetail=true :class="_class"
          :title="name5" :resourceName="resourceName2" :resourceId="resourceIdForEntree" :uriKey="uriKey" :helpWidth=250
          :helpText="helpText5" :refreshWhenActionRuns=false :refreshWhenFiltersChange=false
          v-if="resourceIdForEntree" />

        <ProgressMetric :name="name4" :card="_card4" :prefixComponent=false :onlyOnDetail=true :class="_class"
          :title="name4" :resourceName="resourceName2" :resourceId="resourceIdForEntree" :uriKey="uriKey" :helpWidth=250
          :helpText="helpText4" :refreshWhenActionRuns=false :refreshWhenFiltersChange=false
          v-if="entreeStarted == 1" />
        <!-- End of Optional Metrics -->

        <ValueMetric :name="name6" :card="_card6" :prefixComponent=false :onlyOnDetail=true :class="_class"
          :title="name6" :resourceName="resourceName2" :resourceId="resourceIdForEntree" :uriKey="uriKey2"
          :helpWidth=250 :helpText="helpText6" :refreshWhenActionRuns=false :refreshWhenFiltersChange=false
          v-if="resourceIdForEntree" />

      </div>

    </div>
  </div>
</template>
 <!-- Dark mode script  -->
<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue'
const observer = ref(null)
const dark = ref(false)

onMounted(() => {
  dark.value = document.documentElement.classList.contains('dark')

  observer.value = new MutationObserver((records) => {
    records.forEach((record) => {
      dark.value = record.target.classList.contains('dark')
    })
  })

  observer.value.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class'],
    childList: false,
    characterData: false,
  })
})

onBeforeUnmount(() => {
  observer.value.disconnect()
  observer.value = null
})
</script>
<!-- Dark Mode Script end -->


<script>
import ProgressMetric from '../components/Metrics/ProgressMetric.vue'
import PartitionMetric from '../components/Metrics/PartitionMetric.vue'
import ValueMetric from '../components/Metrics/ValueMetric.vue'
import Preloader from './Preloader.vue'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

export default {
  components: {
    ProgressMetric,
    PartitionMetric,
    Preloader,
    ValueMetric,
  },

  props: {
    events: Object,//We are getting this from the inertia intiial props
  },
  data() {
    return {
      isLoading: false,
      entrees: [],
      selectedEvent: null,
      selectedEntree: null,
      resourceId: null,
      resourceIdForEntree: null,
      entreeStarted: null,
      _class: 'md:col-span-4 min-h-40 h-full',
      resourceName: 'events',
      resourceName2: 'entrees',

      name: 'Event Capacity',
      name2: 'Tickets per land',
      name3: 'Event Omzet',
      name4: "Uitverkocht",
      name5: "Aantal gekochte tickets per:",
      name6: "Entree Omzet",
      name7: "Aantal gescande tickets",
      helpText1: "Percentage van de totale capiciteit van alle entrees bij elkaar opgeteld.",
      helpText2: "Overzicht uit welke landen tickets worden gekocht voor jouw event.",
      helpText3: "Omzet van je event exclusief de servicekosten.",
      helpText4: "Percentage van hoever de entree is uitverkocht.",
      helpText5: null,
      helpText6: null,
      helpText7: "Percentage van de hoeveelheid gescande tickets.",
      _card: {
        refreshWhenActionRuns: false,
        prefixComponent: false,
        uriKey: "event-capacity",
        helpWidth: 250,
        refreshWhenFiltersChange: false,
        url: 'events'
      },
      _card2: {
        refreshWhenActionRuns: false,
        uriKey: "event-bought-tickets-per-country",
        helpWidth: 250,
        refreshWhenFiltersChange: false,
      },
      _card3: {
        refreshWhenActionRuns: false,
        uriKey: "event-omzet",
        helpWidth: 250,
        refreshWhenFiltersChange: false,
        icon: "chart-bar",
        ranges: [
          {
            "label": "Vandaag",
            "value": 1
          },
          {
            "label": "7 Dagen",
            "value": 7
          },
          {
            "label": "Maand",
            "value": 30
          },
          {
            "label": "Kwartaal",
            "value": 91
          },
          {
            "label": "1 Jaar",
            "value": 365
          }],
      },
      _card4: {
        refreshWhenActionRuns: false,
        uriKey: "entree-capacityt",
        helpWidth: 250,
        refreshWhenFiltersChange: false,
      },
      _card5: {
        refreshWhenActionRuns: false,
        uriKey: "aantal-gekochte-tickets-per",
        helpWidth: 250,
        refreshWhenFiltersChange: false,
        icon: "chart-bar",
        ranges: [
          {
            "label": "Vandaag",
            "value": 1
          },
          {
            "label": "7 Dagen",
            "value": 7
          },
          {
            "label": "Maand",
            "value": 30
          },
          {
            "label": "Kwartaal",
            "value": 91
          },
          {
            "label": "1 Jaar",
            "value": 365
          }
        ]
      },
      _card6: {
        refreshWhenActionRuns: false,
        uriKey: "entree-omzet",
        helpWidth: 250,
        refreshWhenFiltersChange: false,
        icon: "chart-bar",
        ranges: [
          {
            "label": "Vandaag",
            "value": 1
          },
          {
            "label": "7 Dagen",
            "value": 7
          },
          {
            "label": "Maand",
            "value": 30
          },
          {
            "label": "Kwartaal",
            "value": 91
          },
          {
            "label": "1 Jaar",
            "value": 365
          }
        ],
      },
      _card7: {
        refreshWhenActionRuns: false,
        uriKey: "scannend-tickets",
        helpWidth: 250,
        refreshWhenFiltersChange: false,
        icon: "chart-bar",
      },

    };
  },

  methods: {
    setEntree: function (event) {
      this.resourceIdForEntree = this.entrees[this.selectedEntree].entree_id;
      const queryForEntreeCount = Nova.request().get('/nova-vendor/fiver-metric-tool/get-entree-count/' + this.resourceIdForEntree).then(response => {
        this.isLoading = true;
        this.entreeStarted = response.data;
        this.isLoading = false;
      })
    },
    getEntrees: function () {
      this.isLoading = true;
      //Clean the selected entree and resource id
      this.resourceIdForEntree = null;
      this.selectedEntree = null;
      this.entreeStarted = null;
      this.resourceId = this.selectedEvent.event_id;

      const query = Nova.request().get('/nova-vendor/fiver-metric-tool/entrees/' + this.selectedEvent.event_id).then(response => {
        this.entrees = response.data;
        this.isLoading = false;
      })
    },
  },
  watch: {
    selectedEvent: function (val, oldval) {
      if (val !== null) {
        this.getEntrees();
      }
    }
  },
}
</script>

<style>
/* Scoped Styles */
</style>
