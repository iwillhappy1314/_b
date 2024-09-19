<div id="modal" _="on closeModal add .closing then wait for animationend then remove me">
    <div class="modal-underlay" _="on click trigger closeModal"></div>
    <div class="modal-content relative">

        <span class="inline-block w-8 h-8 absolute right-4 top-4 cursor-pointer" _="on click trigger closeModal">
            <svg t="1724839185623" class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" p-id="1463" width="24" height="24">
                <path d="M780.309 839.716l-597.298-597.298 60.339-60.339 597.298 597.298-60.339 60.339z" p-id="1464"></path>
                <path d="M243.412 840.144l-60.339-60.339 597.328-597.328 60.339 60.339-597.328 597.328z" p-id="1465"></path>
            </svg>
        </span>

        <h1 class="text-xl">{{$title}}</h1>

        <div id="wpkm-modal-form">
            {!! $form !!}
        </div>
    </div>
</div>