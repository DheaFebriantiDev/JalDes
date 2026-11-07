<template>
  <article 
    @click="$router.push(`/destinasi/${item.id}`)"
    class="group cursor-pointer bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col border border-gray-100"
  >
    
    <!-- Area Gambar -->
    <div class="relative h-52 overflow-hidden bg-gray-100">
      <!-- Gambar Dinamis -->
      <img 
        v-if="item.image"
        :src="item.image" 
        :alt="item.title" 
        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
      />
      <!-- Placeholder Fallback -->
      <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-forest-100 to-emerald-100 text-forest-300">
        <svg class="w-16 h-16 opacity-50 transition-transform duration-500 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div>
      
      <!-- Label Kategori -->
      <span class="absolute top-4 left-4 px-3 py-1.5 rounded-full text-xs font-bold text-white shadow-sm backdrop-blur-md bg-forest-600/90">
        {{ item.category || 'Kategori' }}
      </span>
    </div>

    <!-- Konten Teks -->
    <div class="p-6 flex-1 flex flex-col">
      <h3 class="text-xl font-bold text-gray-900 mb-2 leading-snug group-hover:text-forest-700 transition-colors line-clamp-1">
        {{ item.title || 'Nama Destinasi' }}
      </h3>
      
      <!-- Lokasi dengan Icon Map Pin -->
      <div class="flex items-center gap-1.5 text-sm text-gray-500 mb-3">
        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span class="line-clamp-1">{{ item.village || 'Lokasi Desa' }}</span>
      </div>

      <!-- Deskripsi -->
      <p class="text-sm text-gray-600 leading-relaxed flex-1 mb-6 line-clamp-2">
        {{ item.description || 'Deskripsi singkat destinasi akan tampil di sini.' }}
      </p>

      <!-- Tombol Aksi -->
      <div class="grid grid-cols-2 gap-3 mt-auto">
        <!-- Tombol Maps (Link ke Google Maps) -->
        <a 
          :href="item.mapsUrl" 
          target="_blank" 
          rel="noopener noreferrer"
          @click.stop
          class="flex items-center justify-center gap-1.5 bg-forest-50 hover:bg-forest-600 text-forest-700 hover:text-white text-sm font-semibold px-3 py-2.5 rounded-xl border border-forest-200 transition-colors"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          Maps
        </a>
        
        <!-- Tombol WhatsApp (Link ke WA) -->
        <a 
          :href="item.waUrl" 
          target="_blank" 
          rel="noopener noreferrer"
          @click.stop
          class="flex items-center justify-center gap-1.5 bg-forest-50 hover:bg-green-500 text-forest-700 hover:text-white text-sm font-semibold px-3 py-2.5 rounded-xl border border-forest-200 hover:border-green-500 transition-colors"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
          </svg>
          WhatsApp
        </a>
      </div>
    </div>
  </article>
</template>

<script>
export default { 
  name: 'DestinationCard',
  // Tambahkan props item agar bisa menerima data
  props: {
    item: {
      type: Object,
      required: true,
      default: () => ({})
    }
  }
}
</script>