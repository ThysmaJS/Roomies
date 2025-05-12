<template>
  <div class="room">
    <ChatBox />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import ChatBox from '@/components/Chat/ChatBox.vue';

export default defineComponent({
  name: 'RoomPage',
  components: {
    ChatBox
  }
});
</script>
