<template>
  <div class="ygo-card-container">
    <div class="col row justify-center items-center">
      <q-img
        :src="card.image_path"
        spinner-color="black"
        style="width: 300px"
      />
    </div>
    <slot name="before-desc" />
    <DescMonsterCard
      v-if="card.attribute"
      :card="card"
      class="description-card"
    />
    <DescSpellCard v-else :card="card" />
    <slot name="after-desc" />

    <q-card bordered v-if="card.status_ban || card.main_decks?.length">
    </q-card>

    <q-card bordered>
      <q-table
        class="my-sticky-header-table"
        title="Available in:"
        :rows="card.card_sets"
        :columns="[
          {
            name: 'set_name',
            label: 'Name',
            align: 'left',
            field: (row) => row.set_name,
            format: (val) => `${val}`,
            sortable: true,
          },
          {
            name: 'set_code',
            label: 'Set Code',
            align: 'left',
            field: (row) => row.set_code,
            format: (val) => `${val}`,
            sortable: true,
          },
          {
            name: 'set_rarity',
            label: 'Set Rarity',
            align: 'left',
            field: (row) => row.set_rarity,
            format: (val) => `${val}`,
            sortable: true,
          },
        ]"
        row-key="name"
        flat
      />

      <q-separator />
      <q-card-section v-if="card.status_ban || card.main_decks?.length">
        <div v-if="card.status_ban">
          <span> Status: </span
          ><q-chip :color="card.number_allowed == 0 ? 'negative' : 'warning'">
            {{ card.status_ban }}
          </q-chip>
        </div>
        <p
          v-if="card.main_decks?.filter((deck) => deck.public).length > 0"
          @click="emits('click:show-decks')"
          class="cursor-pointer link"
        >
          {{ card.main_decks?.filter((deck) => deck.public).length }} decks
          plays this card
        </p>
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup>
import { defineEmits, defineProps } from "vue";
import { useRouter } from "vue-router";
import YgoCard from "./YgoCard.vue";
import DescMonsterCard from "src/components/cards/DescMonsterCard.vue";
import DescSpellCard from "src/components/cards/DescSpellCard.vue";

const emits = defineEmits(["click:show-decks"]);

const props = defineProps({
  card: {
    type: Object,
    required: true,
    default: null,
  },
});
</script>

<style scoped lang="scss">
.ygo-card-container {
  // @media (max-width: $breakpoint-md) {
  //   display: flex;
  //   width: 100%;
  //   flex-direction: column;
  //   justify-content: center;
  //   align-items: center;
  // }
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.description-card {
  width: 100%;
}

.my-sticky-header-table {
  /* height or max-height is important */
  height: 310px;

  .q-table__top,
  .q-table__bottom,
  thead tr:first-child th {
    /* bg color is important for th; just specify one */
    background-color: #c1f4cd;
  }

  thead tr th {
    position: sticky;
    z-index: 1;
  }
  thead tr:first-child th {
    top: 0;
  }

  /* this is when the loading indicator appears */
  .q-table--loading thead tr:last-child th {
    /* height of all previous header rows */
    top: 48px;
  }
}
</style>
