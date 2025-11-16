@props(['product'])

@php
  $nama  = $product->nama_barang ?? '-';
  $harga = (int)($product->harga_satuan ?? 0);
  $img   = $product->gambar_url ?? asset('images/placeholder-product.png');
  $rupiah = fn($n) => 'Rp '.number_format($n,0,',','.');
@endphp

<div
  class="relative w-[159px] h-[244px] rounded-[20px] bg-[#F0EEED] shadow-sm"
  x-data="{ qty: 0, add(){ qty = Math.min(qty+1, 99) }, sub(){ qty = Math.max(qty-1, 0) } }"
>
  {{-- image 89x89 @ top:3px left:34px --}}
  <div class="absolute left-[34px] top-[3px] h-[89px] w-[89px] overflow-hidden rounded-md bg-white">
    <img src="{{ $img }}" alt="{{ $nama }}" class="h-full w-full object-contain" loading="lazy">
  </div>

  {{-- title 115x10 @ left:25 top:101 --}}
  <p class="absolute left-[25px] top-[101px] h-[10px] w-[115px] truncate text-center font-semibold text-[8px] leading-[10px] text-black">
    {{ $nama }}
  </p>

  {{-- counter (121x30) @ left:19 top:135 --}}
  <div class="absolute left-[19px] top-[135px] flex h-[30px] w-[121px] items-center justify-between">
    {{-- + button (Type4 30x30) --}}
    <button type="button" aria-label="Tambah" @click="add()"
      class="relative grid h-[30px] w-[30px] place-items-center">
      <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="0.5" y="0.5" width="29" height="29" rx="9.5" stroke="black"/>
        <path d="M14.1894 21.5V9H16.3106V21.5H14.1894ZM9 16.3106V14.1894H21.5V16.3106H9Z" fill="black"/>
      </svg>
    </button>

    {{-- qty 0 (13x25) center --}}
    <span class="text-[20px] font-semibold leading-[25px] text-black/50" x-text="qty">0</span>

    {{-- - button (Remove 30x30) --}}
    <button type="button" aria-label="Kurangi" @click="sub()"
      class="relative grid h-[30px] w-[30px] place-items-center">
      <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="0.5" y="0.5" width="29" height="29" rx="9.5" stroke="black" stroke-opacity="0.5"/>
        <path d="M23 14V17H8V14H23Z" fill="black" fill-opacity="0.5"/>
      </svg>
    </button>
  </div>

  {{-- cart button 136x25 @ center (left: ~11.5) top:178 --}}
  <form method="POST" action="{{ route('cart.add') }}"
        class="absolute left-[11px] top-[178px] h-[25px] w-[136px]">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id_barang ?? $product->id }}">
    <input type="hidden" name="qty" :value="qty">
    <button type="submit"
      class="flex h-[25px] w-full items-center justify-center gap-[5px] rounded-[2px] bg-[#000408] px-[2px] text-white disabled:opacity-50"
      :disabled="qty===0">
      {{-- cart 13x11 --}}
      <svg width="13" height="11" viewBox="0 0 13 11" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0_272_110)">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M3.79175 9.62492C3.79175 9.11866 4.27677 8.70825 4.87508 8.70825C5.47339 8.70825 5.95841 9.11866 5.95841 9.62492C5.95841 10.1312 5.47339 10.5416 4.87508 10.5416C4.27677 10.5416 3.79175 10.1312 3.79175 9.62492Z" fill="#FFF5F5"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M9.75 9.62492C9.75 9.11866 10.235 8.70825 10.8333 8.70825C11.4316 8.70825 11.9167 9.11866 11.9167 9.62492C11.9167 10.1312 11.4316 10.5416 10.8333 10.5416C10.235 10.5416 9.75 10.1312 9.75 9.62492Z" fill="#FFF5F5"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M0 0.458333C0 0.205203 0.242512 0 0.541667 0H2.70833C2.96651 0 3.1888 0.154177 3.23947 0.368382L3.6944 2.29167H12.4583C12.6198 2.29167 12.7728 2.35259 12.8757 2.45784C12.9786 2.56308 13.0207 2.70168 12.9904 2.83586L12.123 6.68471C12.0487 7.00123 11.8452 7.28555 11.5481 7.48791C11.2525 7.68926 10.883 7.79669 10.5036 7.79167H5.24802C4.8687 7.79669 4.49919 7.68926 4.20359 7.48791C3.90663 7.28564 3.70318 7.00146 3.6288 6.6851L2.72393 2.85963C2.72026 2.84698 2.71721 2.83412 2.71481 2.82108L2.26434 0.916667H0.541667C0.242512 0.916667 0 0.711464 0 0.458333ZM3.91123 3.20833L4.69129 6.50613C4.71605 6.61163 4.78389 6.70641 4.88291 6.77386C4.98194 6.84131 5.10585 6.87715 5.23295 6.87508L5.24333 6.875H10.5083L10.5187 6.87508C10.6458 6.87715 10.7697 6.84131 10.8688 6.77386C10.9673 6.70672 11.035 6.61252 11.06 6.5076L11.8036 3.20833H3.91123Z" fill="#FFF5F5"/>
        </g>
        <defs><clipPath id="clip0_272_110"><rect width="13" height="11" fill="white"/></clipPath></defs>
      </svg>
      <span class="font-medium text-[14px] leading-[18px]">Tambah</span>
    </button>
  </form>

  {{-- price 69x19 @ left:13 top:212 --}}
  <div class="absolute left-[13px] top-[212px] h-[19px] w-[69px] font-semibold text-[15px] leading-[19px] text-[#F25019]">
    {{ $rupiah($harga) }}
  </div>
</div>
