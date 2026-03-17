@php
$statusColors = [
    'Active' => 'text-[#34d399]',
    'Expired' => 'text-[#f87171]',
    'Detained' => 'text-[#facc15]',
  ];
  $status = $licence['status'];
  $statusClass = $statusColors[$status] ?? $statusColors['Active'];
@endphp

<div class="w-md m-1"
  x-data="{
    licence:      @js($licence),
    status:       @js($licence['status']),
    statusColors: @js($statusColors),
    statusClass:  @js($statusColors[$status] ?? $statusColors['Expired']),
    handleStatusColors(){
      this.status = this.licence?.status;
      this.statusClass = this.statusColors[this.status] ?? this.statusColors['Expired'];
    },
    get setImage(){
      if (this.licence?.image){
        return '/' + this.licence.image.replace(/^\/+/, '')
      } 
      return '/images/defaults/male.png';
    },

  }"
  @licence-card-updated.window="licence = event.detail; handleStatusColors();"
>

<div {{ $attributes }} style="
    border:1px solid #2c3e50;
    background:#1f2a44;
    border-radius:12px;
    padding:16px;
    color:#ffffff;
">

    <!-- Header -->
    <div style="
        border-bottom:1px solid #34495e;
        padding-bottom:10px;
        margin-bottom:14px;
    ">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <strong style="font-size:15px;letter-spacing:1px;">
                Government Driving Licence
            </strong>

            <span style="font-size:12px;opacity:0.8;">
                ID: <span x-text="licence?.licence_number"></span>
            </span>
        </div>
    </div>

    <!-- Profile -->
    <div style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;">

        <img
            crossorigin="anonymous"
            x-bind:src="setImage"
            alt="licence holder"
            style="
                width:90px;
                height:110px;
                object-fit:cover;
                border:2px solid #3b82f6;
                border-radius:8px;
            "
        >

        <div>

            <div style="font-weight:bold;font-size:18px;"
                 x-text="licence?.person?.name"></div>

            <div style="margin-top:5px;color:#93c5fd;">
                Class: <span x-text="licence?.licence_class?.title"></span>
            </div>

            <div style="margin-top:10px;">
              <span
                class="font-bold"
                x-bind:class="statusClass"
                x-text="status"
              >
            </span>
            </div>
        </div>

    </div>

    <!-- Divider -->
    <div style="border-top:1px solid #34495e;margin:16px 0;"></div>

    <!-- Info Grid -->
    <div style="
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:14px;
        font-size:14px;
    ">

        <div>
            <div style="font-size:12px;color:#9ca3af;">Issue Date</div>
            <div x-text="licence?.issue_date"></div>
        </div>

        <div>
            <div style="font-size:12px;color:#9ca3af;">Expiry Date</div>
            <div x-text="licence?.expiry_date"></div>
        </div>

        <div>
            <div style="font-size:12px;color:#9ca3af;">Issue Reason</div>
            <div x-text="licence?.issue_reason"></div>
        </div>

        <div>
            <div style="font-size:12px;color:#9ca3af;">Notes</div>
            <div x-text="licence?.notes"></div>
        </div>

    </div>

</div>
</div>