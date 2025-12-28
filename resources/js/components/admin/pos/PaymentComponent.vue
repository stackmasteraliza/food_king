<template>
  <LoadingComponent :props="loading" />

  <div id="orderpayment" class="modal">
    <div class="modal-dialog max-w-[460px] w-full">
      <div class="modal-header pb-3 border-b border-[#D9DBE9]">
        <h3 class="capitalize font-medium">{{ $t("label.Order Payment") }}</h3>
        <button
          class="modal-close fa-regular fa-circle-xmark"
          @click="reset"
        ></button>
      </div>
      <div class="modal-body">
        <div class="mb-4">
          <div
            class="flex justify-between items-center h-12 w-full rounded-lg py-1.5 px-2 placeholder:text-[10px] placeholder:text-[#6E7191] bg-[#F7F7FC]"
          >
            <span class="text-sm font-normal text-[#2E2F38]">{{
              $t("label.Total Amount")
            }}</span>
            <span
              style="font-family: 'saudi_riyal'"
              class="text-primary text-base font-medium"
              >{{
                currencyFormat(
                  props.form.total,
                  setting.site_digit_after_decimal_point,
                  setting.site_default_currency_symbol,
                  setting.site_currency_position
                )
              }}
              &#xE900;
            </span>
          </div>
        </div>
        <div class="mb-4">
          <h3 class="capitalize font-medium mb-2">
            {{ $t("label.Select Payment Method") }}
          </h3>
          <nav class="flex flex-wrap gap-4 active-group">
            <button
              data-tab="#cash"
              type="button"
              class="other-tabBtn w-fit flex flex-col items-center gap-2 rounded-lg py-3 px-7 border bg-[#F7F7FC] border-[#F7F7FC]"
              :class="
                props.form.pos_payment_method === posPaymentMethodEnum.CASH
                  ? 'active'
                  : ''
              "
              @click="paymentMethod(posPaymentMethodEnum.CASH, 'cashInput')"
            >
              <i class="lab lab-cash lab-font-size-24"></i>
              <span
                class="text-xs font-medium leading-none text-heading"
                style="font-size: larger; font-weight: bold"
                >{{ $t("label.cash") }}</span
              >
            </button>
            <button
              data-tab="#card"
              type="button"
              class="other-tabBtn w-fit flex flex-col items-center gap-2 rounded-lg py-3 px-7 border bg-[#F7F7FC] border-[#F7F7FC]"
              :class="
                props.form.pos_payment_method === posPaymentMethodEnum.CARD
                  ? 'active'
                  : ''
              "
              @click="paymentMethod(posPaymentMethodEnum.CARD, 'cardInput')"
            >
              <i class="lab lab-card-2 lab-font-size-24"></i>
              <span
                class="text-xs font-medium leading-none text-heading"
                style="font-size: larger; font-weight: bold"
                >{{ $t("نقد+شبكة") }}
              </span>
            </button>
            <button
              data-tab="#Mada"
              type="button"
              class="other-tabBtn w-fit flex flex-col items-center gap-2 rounded-lg py-3 px-7 border bg-[#F7F7FC] border-[#F7F7FC]"
              :class="
                props.form.pos_payment_method === posPaymentMethodEnum.Mada
                  ? 'active'
                  : ''
              "
              @click="paymentMethod(posPaymentMethodEnum.Mada)"
            >
              <svg
                version="1.1"
                id="Layer_1"
                xmlns="http://www.w3.org/2000/svg"
                width="32px"
                height="32px"
                viewBox="0 0 796.2 265.5"
                style="enable-background: new 0 0 796.2 265.5"
                preserveAspectRatio="xMidYMid meet"
              >
                <g>
                  <rect y="153.1" class="st0" width="336.8" height="112.2" />
                  <rect class="st1" width="336.8" height="112.3" />
                  <path
                    class="st2"
                    d="M673.6,242.5l-1.5,0.3c-5.2,1-7.1,1.4-10.9,1.4c-8.8,0-19.2-4.5-19.2-25.7c0-10.9,1.8-25.4,18.2-25.4h0.1
		c2.8,0.2,6,0.5,12,2.3l1.3,0.4L673.6,242.5L673.6,242.5z M676.3,136.8l-2.7,0.5v39.2l-2.4-0.7l-0.7-0.2c-2.7-0.8-8.9-2.6-14.9-2.6
		c-32.8,0-39.7,24.8-39.7,45.6c0,28.5,16,44.9,43.9,44.9c11.8,0,20.5-1.2,29.3-4.1c8.1-2.6,11-6.3,11-14.2V132.7
		C692.3,134.1,684.2,135.5,676.3,136.8"
                  />
                  <path
                    class="st2"
                    d="M771.1,243.2l-1.4,0.4l-5,1.3c-4.7,1.2-8.9,1.9-12.1,1.9c-7.7,0-12.3-3.8-12.3-10.3c0-4.2,1.9-11.3,14.5-11.3
		h16.3V243.2z M759.6,172.5c-10.1,0-20.5,1.8-33.4,5.8l-8.4,2.5l2.8,19l8.2-2.7c8.6-2.8,19.3-4.6,27.3-4.6c3.6,0,14.6,0,14.6,11.9
		v5.2h-15.3c-27.9,0-40.8,8.9-40.8,28c0,16.3,11.9,26.1,31.9,26.1c6.2,0,14.8-1.2,22.2-3l0.4-0.1l0.4,0.1l2.5,0.4
		c7.8,1.4,15.9,2.8,23.8,4.3V203C795.8,182.8,783.6,172.5,759.6,172.5"
                  />
                  <path
                    class="st2"
                    d="M576.8,243.2l-1.4,0.4l-5,1.3c-4.7,1.2-8.8,1.9-12.1,1.9c-7.7,0-12.3-3.8-12.3-10.3c0-4.2,1.9-11.3,14.4-11.3
		h16.3L576.8,243.2L576.8,243.2z M565.4,172.5c-10.2,0-20.5,1.8-33.4,5.8l-8.4,2.5l2.8,19l8.2-2.7c8.6-2.8,19.3-4.6,27.3-4.6
		c3.6,0,14.6,0,14.6,11.9v5.2h-15.3c-27.9,0-40.9,8.9-40.9,28c0,16.3,11.9,26.1,32,26.1c6.2,0,14.8-1.2,22.2-3l0.4-0.1l0.4,0.1
		l2.4,0.4c7.9,1.4,15.9,2.8,23.8,4.4v-62.4C601.6,182.7,589.4,172.5,565.4,172.5"
                  />
                  <path
                    class="st2"
                    d="M471.5,172.7c-12.7,0-23.2,4.2-27.1,6l-1,0.5l-0.9-0.7c-5.4-3.9-13.3-5.9-24.3-5.9c-9.7,0-18.8,1.4-28.7,4.3
		c-8.5,2.6-11.8,6.7-11.8,14.4v71.3h26.6v-65.9l1.3-0.4c5.4-1.8,8.6-2.1,11.7-2.1c7.7,0,11.6,4.1,11.6,12.1v56.4h26.2v-57.5
		c0-3.4-0.7-5.4-0.8-5.8l-0.9-1.7l1.8-0.8c4-1.8,8.4-2.7,13-2.7c5.3,0,11.6,2.1,11.6,12.1v56.4h26.1v-59
		C505.9,182.8,494.7,172.7,471.5,172.7"
                  />
                  <path
                    class="st2"
                    d="M751.5,73.2c-3.9,0-10.4-0.4-15.5-1.4l-1.5-0.3V33c0-3.2-0.6-5.2-0.7-5.5l-0.8-1.6l1.7-0.7
		c0.4-0.2,0.8-0.3,1.3-0.5l0.3-0.2c0.6-0.2,1.2-0.4,1.8-0.6c0.3-0.1,0.5-0.2,0.7-0.2c5.9-1.6,11.3-1.4,13.7-1.6h0.1
		c16.3,0,18.2,14.5,18.2,25.4C770.7,68.7,760.2,73.2,751.5,73.2 M751.4,0c-0.2,0-0.5,0-0.7,0c-15.3,0-31,4.2-36.6,12.4
		c-3,4-4.7,9-4.8,14.9l0,0V67c0,3.4-0.7,4.7-0.8,5l-0.9,1.7h-48.3V46.1h-0.1C658.6,17,641.4,1,616.5,1h-2.9h-21.4
		c-1,7.1-1.8,12.1-2.8,19.2h24.2c12.7,0,19.4,10.8,19.4,27.4v27.8l-1.7-0.9c-0.3-0.1-2.4-0.8-5.7-0.8h-41.8
		c-0.8,5.3-1.8,12.2-2.9,19.1h128.5c4.4-0.9,9.5-1.7,13.9-2.4c6.5,3.2,18.6,4.9,26.9,4.9c27.9,0,46-18.7,46-47.5
		C796.1,19.3,778.6,0.6,751.4,0"
                  />
                  <path
                    class="st2"
                    d="M526.1,104.5h1.2c27.9,0,40.9-9.2,40.9-31.9c0-16.3-11.9-29.3-31.9-29.3h-25.7c-7.7,0-12.3-4.4-12.3-11.8
		c0-5,1.9-11.2,14.5-11.2H569c1.2-7.3,1.8-11.9,2.9-19.2h-58.4c-27.2,0-40.9,11.4-40.9,30.4c0,18.8,11.9,28.6,31.9,28.6h25.7
		c7.7,0,12.3,6.1,12.3,12.5c0,4.2-1.9,12.9-14.4,12.9h-4.3l-82.3-0.2l0,0h-15c-12.7,0-21.6-7.2-21.6-23.9V49.9
		c0-17.4,6.9-28.2,21.6-28.2h24.4c1.1-7.4,1.8-12.1,2.8-19.1h-30.4h-2.9c-24.9,0-42.1,16.7-42.7,45.8l0,0v1.1v11.9
		c0.6,29.1,17.8,43,42.7,43h2.9h21.4l44.6,0.1l0,0h26.6L526.1,104.5L526.1,104.5z"
                  />
                </g>
              </svg>
              <span
                class="text-xs font-medium leading-none text-heading"
                style="font-size: larger; font-weight: bold"
                >{{ $t("مدى") }}</span
              >
            </button>
            <button
              data-tab="#Viza"
              type="button"
              class="other-tabBtn w-fit flex flex-col items-center gap-2 rounded-lg py-3 px-7 border bg-[#F7F7FC] border-[#F7F7FC]"
              :class="
                props.form.pos_payment_method === posPaymentMethodEnum.Viza
                  ? 'active'
                  : ''
              "
              @click="paymentMethod(posPaymentMethodEnum.Viza)"
            >
              <svg
                fill="#000000"
                width="32px"
                height="32px"
                viewBox="0 0 32 32"
                version="1.1"
                xmlns="http://www.w3.org/2000/svg"
              >
                <title>visa</title>
                <path
                  d="M15.854 11.329l-2.003 9.367h-2.424l2.006-9.367zM26.051 17.377l1.275-3.518 0.735 3.518zM28.754 20.696h2.242l-1.956-9.367h-2.069c-0.003-0-0.007-0-0.010-0-0.459 0-0.853 0.281-1.019 0.68l-0.003 0.007-3.635 8.68h2.544l0.506-1.4h3.109zM22.429 17.638c0.010-2.473-3.419-2.609-3.395-3.714 0.008-0.336 0.327-0.694 1.027-0.785 0.13-0.013 0.28-0.021 0.432-0.021 0.711 0 1.385 0.162 1.985 0.452l-0.027-0.012 0.425-1.987c-0.673-0.261-1.452-0.413-2.266-0.416h-0.001c-2.396 0-4.081 1.275-4.096 3.098-0.015 1.348 1.203 2.099 2.122 2.549 0.945 0.459 1.262 0.754 1.257 1.163-0.006 0.63-0.752 0.906-1.45 0.917-0.032 0.001-0.071 0.001-0.109 0.001-0.871 0-1.691-0.219-2.407-0.606l0.027 0.013-0.439 2.052c0.786 0.315 1.697 0.497 2.651 0.497 0.015 0 0.030-0 0.045-0h-0.002c2.546 0 4.211-1.257 4.22-3.204zM12.391 11.329l-3.926 9.367h-2.562l-1.932-7.477c-0.037-0.364-0.26-0.668-0.57-0.82l-0.006-0.003c-0.688-0.338-1.488-0.613-2.325-0.786l-0.066-0.011 0.058-0.271h4.124c0 0 0.001 0 0.001 0 0.562 0 1.028 0.411 1.115 0.948l0.001 0.006 1.021 5.421 2.522-6.376z"
                ></path>
              </svg>

              <span
                class="text-xs font-medium leading-none text-heading"
                style="font-size: larger; font-weight: bold"
                >{{ $t("فيزا") }}</span
              >
            </button>
            <!-- <button
              data-tab="#mfs"
              type="button"
              onclick="createkeyboard('mfs')"
              class="other-tabBtn w-fit flex flex-col items-center gap-2 rounded-lg py-3 px-7 border bg-[#F7F7FC] border-[#F7F7FC]"
              :class="
                props.form.pos_payment_method ===
                posPaymentMethodEnum.MOBILE_BANKING
                  ? 'active'
                  : ''
              "
              @click="paymentMethod(posPaymentMethodEnum.MOBILE_BANKING)"
            >
              <i class="lab lab-mfs lab-font-size-24"></i>
              <span class="text-xs font-normal leading-none text-heading">{{
                $t("label.mobile_banking")
              }}</span>
            </button> -->

            <!-- <button
              data-tab="#otherpay"
              type="button"
              onclick="createkeyboard('otherpay')"
              class="other-tabBtn w-fit flex flex-col items-center gap-2 rounded-lg py-3 px-7 border bg-[#F7F7FC] border-[#F7F7FC]"
              :class="
                props.form.pos_payment_method === posPaymentMethodEnum.OTHER
                  ? 'active'
                  : ''
              "
              @click="paymentMethod(posPaymentMethodEnum.OTHER)"
            >
              <i class="lab lab-other lab-font-size-24"></i>
              <span
                class="text-xs font-medium leading-none text-heading"
                style="font-size: larger; font-weight: bold"
                >{{ $t("label.other") }}</span
              >
            </button> -->
          </nav>
        </div>
        <div
          id="cash"
          class="data-tab hidden"
          :class="
            props.form.pos_payment_method === posPaymentMethodEnum.CASH
              ? 'active'
              : ''
          "
        >
          <div class="mb-4">
            <h3 class="capitalize font-medium mb-2">
              {{ $t("label.received_amount") }}
            </h3>
            <input
              id="cashInput"
              ref="cashInput"
              type="text"
              v-on:keypress="floatNumber($event)"
              class="h-12 w-full rounded-lg border py-1.5 px-4 border-[#D9DBE9] text-black"
            />
          </div>
        </div>
        <div
          id="card"
          class="data-tab hidden"
          :class="
            props.form.pos_payment_method === posPaymentMethodEnum.CARD
              ? 'active'
              : ''
          "
        >
          <table class="mb-4">
              <tbody>
                <tr>
                  <td>
                    <h3 class="capitalize font-medium mb-2">
                      {{ $t("مبلغ النقد") }}
                    </h3>
                  </td>
                  <td>
                    <h3 class="capitalize font-medium mb-2">
                      {{ $t("مبلغ الشبكه") }}
                    </h3>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input
                      id="CashCardInput"
                      type="text"
                      ref="CashCardInput"
                      v-on:keypress="floatNumber($event)"
                      class="h-12 rounded-lg border py-1.5 px-4 border-[#D9DBE9] text-black"
                    />
                  </td>
                  <td>
                    <input
                      id="cardInput"
                      type="text"
                      v-on:keypress="floatNumber($event)"
                      ref="cardInput"
                      class="h-12 rounded-lg border py-1.5 px-4 border-[#D9DBE9] text-black"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
        </div>
        <div
          id="Mada"
          class="data-tab hidden"
          :class="
            props.form.pos_payment_method === posPaymentMethodEnum.Mada
              ? 'active'
              : ''
          "
        >
          <div class="mb-4">
            <h3 class="capitalize font-medium mb-2">
              {{ $t("label.enter_transaction_id") }}
            </h3>
            <input
              id="mada-trans"
              type="number"
              ref="madaInput"
              class="h-12 w-full rounded-lg border py-1.5 px-4 placeholder:text-xs border-[#D9DBE9]"
            />
          </div>
          <div
            class="board grid grid-cols-10 justify-between gap-1.5 mb-6"
          ></div>
        </div>
        <div
          id="Viza"
          class="data-tab hidden"
          :class="
            props.form.pos_payment_method === posPaymentMethodEnum.Viza
              ? 'active'
              : ''
          "
        >
          <div class="mb-4">
            <h3 class="capitalize font-medium mb-2">
              {{ $t("label.enter_transaction_id") }}
            </h3>
            <input
              id="Viza-trans"
              type="number"
              ref="FizaInput"
              class="h-12 w-full rounded-lg border py-1.5 px-4 placeholder:text-xs border-[#D9DBE9]"
            />
          </div>
          <div
            class="board grid grid-cols-10 justify-between gap-1.5 mb-6"
          ></div>
        </div>
        <!-- <div
          id="mfs"
          class="data-tab hidden"
          :class="
            props.form.pos_payment_method ===
            posPaymentMethodEnum.MOBILE_BANKING
              ? 'active'
              : ''
          "
        >
          <div class="mb-4">
            <h3 class="capitalize font-medium mb-2">
              {{ $t("label.enter_transaction_id") }}
            </h3>
            <input
              id="mfs-trans"
              type="text"
              ref="mfsInput"
              class="h-12 w-full rounded-lg border py-1.5 px-4 placeholder:text-xs border-[#D9DBE9]"
            />
          </div>
          <div
            class="board grid grid-cols-10 justify-between gap-1.5 mb-6"
          ></div>
        </div> -->
        <div
          id="otherpay"
          class="data-tab hidden"
          :class="
            props.form.pos_payment_method === posPaymentMethodEnum.OTHER
              ? 'active'
              : ''
          "
        >
          <div class="mb-4">
            <h3 class="capitalize font-medium mb-2">
              {{ $t("label.enter_payment_note") }}
            </h3>
            <input
              id="other-trans"
              type="text"
              ref="otherInput"
              class="h-12 w-full rounded-lg border py-1.5 px-4 placeholder:text-xs border-[#D9DBE9]"
            />
          </div>
          <div
            class="board grid grid-cols-10 justify-between gap-1.5 mb-6"
          ></div>
        </div>

        <div
          class="grid grid-cols-4 gap-x-4 gap-y-3.5 mb-6"
          v-if="
            props.form.pos_payment_method === posPaymentMethodEnum.CASH ||
            props.form.pos_payment_method === posPaymentMethodEnum.CARD ||
            props.form.pos_payment_method === posPaymentMethodEnum.Mada ||
            props.form.pos_payment_method === posPaymentMethodEnum.Viza
          "
        >
          <button
            :onclick="`solve('1', '${inputIdName}')`"
            value="1"
            class="num bg-[#F7F7FC] rounded-lg p-2.5 flex items-center justify-center text-base font-medium text-[#1F1F39]"
          >
            1
          </button>
          <button
            :onclick="`solve('2', '${inputIdName}')`"
            value="2"
            class="num bg-[#F7F7FC] rounded-lg p-2.5 flex items-center justify-center text-base font-medium text-[#1F1F39]"
          >
            2
          </button>
          <button
            :onclick="`solve('3', '${inputIdName}')`"
            value="3"
            class="num bg-[#F7F7FC] rounded-lg p-2.5 flex items-center justify-center text-base font-medium text-[#1F1F39]"
          >
            3
          </button>
          <button
            :onclick="`Back('${inputIdName}')`"
            value="cut"
            class="num bg-[#F7F7FC] rounded-lg p-2.5 flex items-center justify-center text-base font-medium text-[#1F1F39] row-span-2"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
            >
              <path
                d="M16.9997 3.75H10.2797C8.86969 3.75 7.52969 4.34 6.57969 5.39L3.04969 9.27C1.63969 10.82 1.63969 13.18 3.04969 14.73L6.57969 18.61C7.52969 19.65 8.86969 20.25 10.2797 20.25H16.9997C19.7597 20.25 21.9997 18.01 21.9997 15.25V8.75C21.9997 5.99 19.7597 3.75 16.9997 3.75ZM16.5297 13.94C16.8197 14.23 16.8197 14.71 16.5297 15C16.3797 15.15 16.1897 15.22 15.9997 15.22C15.8097 15.22 15.6197 15.15 15.4697 15L13.5297 13.06L11.5897 15C11.4397 15.15 11.2497 15.22 11.0597 15.22C10.8697 15.22 10.6797 15.15 10.5297 15C10.2397 14.71 10.2397 14.23 10.5297 13.94L12.4697 12L10.5297 10.06C10.2397 9.77 10.2397 9.29 10.5297 9C10.8197 8.71 11.2997 8.71 11.5897 9L13.5297 10.94L15.4697 9C15.7597 8.71 16.2397 8.71 16.5297 9C16.8197 9.29 16.8197 9.77 16.5297 10.06L14.5897 12L16.5297 13.94Z"
                fill="#1F1F39"
              />
            </svg>
          </button>
          <button
            :onclick="`solve('4', '${inputIdName}')`"
            value="4"
            class="num bg-[#F7F7FC] rounded-lg p-2.5 flex items-center justify-center text-base font-medium text-[#1F1F39]"
          >
            4
          </button>
          <button
            :onclick="`solve('5', '${inputIdName}')`"
            value="5"
            class="num bg-[#F7F7FC] rounded-lg p-2.5 flex items-center justify-center text-base font-medium text-[#1F1F39]"
          >
            5
          </button>
          <button
            :onclick="`solve('6', '${inputIdName}')`"
            value="6"
            class="num bg-[#F7F7FC] rounded-lg p-2.5 flex items-center justify-center text-base font-medium text-[#1F1F39]"
          >
            6
          </button>
          <button
            :onclick="`solve('7', '${inputIdName}')`"
            value="7"
            class="num bg-[#F7F7FC] rounded-lg p-2.5 flex items-center justify-center text-base font-medium text-[#1F1F39]"
          >
            7
          </button>
          <button
            :onclick="`solve('8', '${inputIdName}')`"
            value="8"
            class="num bg-[#F7F7FC] rounded-lg p-2.5 flex items-center justify-center text-base font-medium text-[#1F1F39]"
          >
            8
          </button>
          <button
            :onclick="`solve('9', '${inputIdName}')`"
            value="9"
            class="num bg-[#F7F7FC] rounded-lg p-2.5 flex items-center justify-center text-base font-medium text-[#1F1F39]"
          >
            9
          </button>
          <button
            :onclick="`Clear('${inputIdName}')`"
            value="clear"
            type="reset"
            class="num bg-[#F7F7FC] rounded-lg p-2.5 flex items-center justify-center text-base font-medium text-[#1F1F39] row-span-2"
          >
            Clear
          </button>
          <button
            :onclick="`solve('00', '${inputIdName}')`"
            value="00"
            class="num bg-[#F7F7FC] rounded-lg p-2.5 flex items-center justify-center text-base font-medium text-[#1F1F39]"
          >
            00
          </button>
          <button
            :onclick="`solve('0', '${inputIdName}')`"
            value="0"
            class="num bg-[#F7F7FC] rounded-lg p-2.5 flex items-center justify-center text-base font-medium text-[#1F1F39]"
          >
            0
          </button>
          <button
            :onclick="`solve('.', '${inputIdName}')`"
            value="point"
            class="num bg-[#F7F7FC] rounded-lg p-2.5 flex items-center justify-center text-base font-medium text-[#1F1F39]"
          >
            .
          </button>
        </div>
        <button
          @click="confirmOrder"
          type="button"
          class="rounded-3xl text-base py-2 px-3 font-medium w-full text-white bg-primary"
        >
          {{ $t("button.Confirm & Print Reciept") }}
        </button>
      </div>
    </div>
  </div>

  <ReceiptComponent :order="order" />
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import appService from "../../../services/appService";
import alertService from "../../../services/alertService";
import ReceiptComponent from "./ReceiptComponent";
import posPaymentMethodEnum from "../../../enums/modules/posPaymentMethodEnum";
import sourceEnum from "../../../enums/modules/sourceEnum";
import isAdvanceOrderEnum from "../../../enums/modules/isAdvanceOrderEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";

export default {
  name: "PaymentComponent",
  components: { LoadingComponent, ReceiptComponent },
  props: {
    props: Object,
  },
  data() {
    return {
      loading: {
        isActive: false,
      },
      order: {},
      posPaymentMethodEnum: posPaymentMethodEnum,
      inputIdName: "cashInput",
    };
  },
  computed: {
    setting: function () {
      return this.$store.getters["frontendSetting/lists"];
    },
  },
  mounted() {},
  methods: {
    currencyFormat: function (amount, decimal, currency, position) {
      return appService.currencyFormat(amount, decimal, currency, position);
    },
    floatNumber(e) {
      return appService.floatNumber(e);
    },
    reset: function () {
      Object.keys(this.$refs).forEach((refName) => {
        if (this.$refs[refName].value !== undefined) {
          this.$refs[refName].value = "";
        }
      });
      this.$props.props.form.pos_payment_note = "";
      appService.modalHide("#orderpayment");
    },
    paymentMethod: function (method, Idname = "") {
      if (Idname) {
        this.inputIdName = Idname;
      }

      Object.keys(this.$refs).forEach((refName) => {
        if (this.$refs[refName].value !== undefined) {
          this.$refs[refName].value = "";
        }
      });
      this.$props.props.form.pos_payment_method = method;
      this.$props.props.form.pos_payment_note = "";
    },
    confirmOrder: function () {
      try {
        if (
          this.$props.props.form.pos_payment_method ===
            this.posPaymentMethodEnum.CASH &&
          this.$refs.cashInput.value
        ) {
          this.$props.props.form.pos_received_amount =
            this.$refs.cashInput.value;
        } else {
          if (
            this.$props.props.form.pos_payment_method ===
              this.posPaymentMethodEnum.CARD &&
            this.$refs.CashCardInput.value
          ) {
            this.$props.props.form.pos_received_amount =
              this.$refs.CashCardInput.value;
          } else {
            this.$props.props.form.pos_received_amount = null;
          }
        }
 this.$props.props.form.NetworkAmount = null;
        if (
          this.$props.props.form.pos_payment_method ===
            this.posPaymentMethodEnum.CARD &&
          this.$refs.cardInput.value
        ) {
          this.$props.props.form.NetworkAmount = this.$refs.cardInput.value;
          this.$props.props.form.pos_payment_note = this.$refs.cardInput.value;
        } else if (
          this.$props.props.form.pos_payment_method ===
            this.posPaymentMethodEnum.MOBILE_BANKING &&
          this.$refs.mfsInput.value
        ) {
          this.$props.props.form.pos_payment_note = this.$refs.mfsInput.value;
        } else if (
          this.$props.props.form.pos_payment_method ===
            this.posPaymentMethodEnum.OTHER &&
          this.$refs.otherInput.value
        ) {
          this.$props.props.form.pos_payment_note = this.$refs.otherInput.value;
        } else if (
          this.$props.props.form.pos_payment_method ===
            this.posPaymentMethodEnum.Mada &&
          this.$refs.madaInput.value
        ) {
          this.$props.props.form.pos_payment_note = this.$refs.madaInput.value;
        } else if (
          this.$props.props.form.pos_payment_method ===
            this.posPaymentMethodEnum.Viza &&
          this.$refs.FizaInput.value
        ) {
          this.$props.props.form.pos_payment_note = this.$refs.FizaInput.value;
        } else {
          this.$props.props.form.pos_payment_note = "";
        }

        this.$store
          .dispatch("defaultAccess/show")
          .then((res) => {
            this.$props.props.form.branch_id = res.data.data.branch_id;
            this.$store
              .dispatch("posOrder/save", this.$props.props.form)
              .then((orderResponse) => {
                this.$props.props.form.token = "";
                this.$props.props.form.subtotal = null;
                this.$props.props.form.discount = 0;
                this.$props.props.form.delivery_time = null;
                this.$props.props.form.delivery_charge = null;
                this.$props.props.form.total = 0;
                this.$props.props.form.order_type = orderTypeEnum.DINING_TABLE;
                this.$props.props.form.is_advance_order = isAdvanceOrderEnum.NO;
                this.$props.props.form.source = sourceEnum.POS;
                this.$props.props.form.address_id = null;
                this.$props.props.form.dining_table_id = null;
                this.$props.props.form.coupon_id = null;
                this.$props.props.form.items = [];
                this.$props.props.form.pos_payment_method =
                  this.posPaymentMethodEnum.CASH;
                this.$props.props.form.pos_payment_note = null;
                this.$props.props.form.pos_received_amount = null;
                this.$props.props.form.NetworkAmount = null;
                appService.modalHide("#orderpayment");
                this.$store
                  .dispatch("posCart/resetCart")
                  .then((res) => {
                    this.loading.isActive = false;
                  })
                  .catch();
                this.$store
                  .dispatch("posOrder/show", orderResponse.data.data.id)
                  .then((res) => {
                    this.order = res.data.data;
                    this.loading.isActive = false;
                  })
                  .catch((error) => {
                    this.loading.isActive = false;
                    alertService.error(error.response.data.message);
                  });
                this.reset();
                try {
                  this.$props.props.form.Reservations = null;
                  this.$props.props.form.reservationDate = null;
                  this.$props.props.form.reservationamount = null;
                } catch {}
                appService.modalShow("#receiptModal");
              })
              .catch((err) => {
                this.loading.isActive = false;
                if (typeof err.response.data.errors === "object") {
                  _.forEach(err.response.data.errors, (error) => {
                    alertService.error(error[0]);
                  });
                }
              });
          })
          .catch((err) => {
            this.loading.isActive = false;
          });
      } catch (err) {
        this.loading.isActive = false;
        alertService.error(err);
      }
    },
  },
};
</script>
<style type="text/css">
.st0 {
  fill: #84b740;
}
.st1 {
  fill: #259bd6;
}
.st2 {
  fill: #27292d;
}
</style>
