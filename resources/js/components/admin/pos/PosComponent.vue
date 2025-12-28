<template>
  <LoadingComponent :props="loading" />

  <div id ="PosSessionModel"
    class="md:w-[calc(100%-340px)] lg:w-[calc(100%-320px)] xl:w-[calc(100%-377px)]"
  >
    <form
      @submit.prevent="search"
      class="flex items-center w-full h-[38px] leading-[38px] mb-4 rounded-lg bg-white"
    >
      <input
        type="text"
        v-model="props.search.name"
        :placeholder="$t('label.search_by_menu_item')"
        class="w-full px-5 rounded-tl-lg rounded-bl-lg border placeholder:text-xs placeholder:font-rubik placeholder:text-[#A0A3BD] border-[#EFF0F6]"
      />
      <button
        type="submit"
        class="flex-shrink-0 w-[38px] h-full text-center ltr:rounded-tr-lg ltr:rounded-br-lg rtl:rounded-tl-lg rtl:rounded-bl-lg bg-primary"
      >
        <i class="lab lab-search-normal text-white"></i>
      </button>
    </form>

    <div class="swiper pos-menu-swiper mb-6" v-if="categories.length > 1">
      <Swiper
        dir="ltr"
        :speed="1000"
        slidesPerView="auto"
        :spaceBetween="16"
        class="menu-slides"
      >
        <SwiperSlide
          class="!w-fit"
          v-for="(category, index) in categories"
          :key="category"
          :class="
            category.id === props.search.item_category_id ||
            (category.id === 0 && props.search.item_category_id === '')
              ? 'pos-group'
              : ''
          "
        >
          <router-link
            v-if="index === 0"
            to="#"
            @click.prevent="allCategory"
            class="w-28 flex flex-col items-center text-center gap-4 py-4 px-3 rounded-lg border-b-2 border-transparent transition hover:bg-[#FFEDF4] hover:border-primary bg-white"
          >
            <img
              class="h-7 drop-shadow-category"
              :src="category.thumb"
              alt="category"
            />
            <h3 class="text-xs leading-[16px] font-medium font-rubik">
              {{ category.name }}
            </h3>
          </router-link>
          <router-link
            v-else
            to="#"
            @click.prevent="setCategory(category.id)"
            class="w-28 flex flex-col items-center text-center gap-4 py-4 px-3 rounded-lg border-b-2 border-transparent transition hover:bg-[#FFEDF4] hover:border-primary bg-white"
          >
            <img
              class="h-7 drop-shadow-category"
              :src="category.thumb"
              alt="category"
            />
            <h3 class="text-xs leading-[16px] font-medium font-rubik">
              {{ category.name }}
            </h3>
          </router-link>
        </SwiperSlide>
      </Swiper>
    </div>
    <ItemComponent :items="items" v-if="items.length > 0" />

    <div class="mt-12" v-else>
      <div class="max-w-[250px] mx-auto">
        <img
          class="w-full mb-8"
          :src="setting.item_not_found"
          alt="image_order_not_found"
        />
      </div>
      <span class="w-full mb-4 text-center text-black">{{
        $t("message.no_items_found")
      }}</span>
    </div>
  </div>

  <div
    id="pos-cart"
    class="db-pos-cartDiv fixed top-0 ltr:right-0 rtl:left-0 w-full h-screen rounded-none z-50 md:z-10 md:top-[85px] ltr:md:right-5 rtl:md:left-5 md:w-[322px] lg:w-[305px] xl:w-[360px] md:h-[calc(100vh-85px)] md:rounded-lg overflow-y-auto thin-scrolling bg-white"
  >
    <div class="p-4">
      <div class="md:hidden text-right mb-3">
        <button class="db-pos-cartCls" @click="closePosCart('pos-cart')">
          <i
            class="lab-close-circle-line font-fill-danger lab-font-size-24"
          ></i>
        </button>
      </div>
      <div class="flex items-center w-full gap-4 mb-3">
        <div class="db-field flex-grow">
          <vue-select
            class="db-field-control text-sm rounded-lg appearance-none text-heading border-[#D9DBE9]"
            id="customer"
            v-model="checkoutProps.form.customer_id"
            :options="customers"
            @update:modelValue="changingUser"
            label-by="name"
            value-by="id"
            :closeOnSelect="true"
            :searchable="true"
            :clearOnClose="true"
            :placeholder="$t('label.select_customer')"
            :search-placeholder="$t('label.search_customer')"
          />
        </div>
        <div
          data-modal="#addCustomer"
          @click.prevent="addCustomers"
          class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center cursor-pointer"
        >
          <i class="fa-solid fa-circle-plus text-white"></i>
        </div>
      </div>
      <input
        class="db-field-control text-sm rounded-lg appearance-none text-heading border-[#D9DBE9] mb-3"
        id="token"
        v-model="checkoutProps.form.token"
        :placeholder="$t('label.token_no')"
      />

      <div class="p-3 pt-2 rounded-lg border border-[#D9DBE9]">
        <h4 class="text-sm font-medium mb-3">
          {{ $t("label.select_order_type") }}
        </h4>

        <div class="db-field-radio-group gap-1 active-group">
          <label
            @click="dineInOrder"
            ref="dineIn"
            for="dinein"
            data-dine="#dine"
            class="!w-fit db-field-radio px-2.5 py-2 rounded-lg border border-[#F7F7FC] bg-[#F7F7FC] active"
          >
            <div class="custom-radio sm">
              <input
                ref="dineInInput"
                type="radio"
                id="dinein"
                name="orderType"
                :value="orderTypeEnums.dineIn"
                v-model="checkoutProps.form.order_type"
                class="custom-radio-field"
              />
              <span class="custom-radio-span"></span>
            </div>
            <h3 class="db-field-label text-sm text-heading">محلي</h3>
          </label>
          <label
            ref="takeAway"
            @click="takeAwayOrder"
            for="takeway"
            class="!w-fit db-field-radio px-2.5 py-2 rounded-lg border border-[#F7F7FC] bg-[#F7F7FC]"
          >
            <div class="custom-radio sm">
              <input
                ref="takeAwayInput"
                type="radio"
                id="takeway"
                name="orderType"
                :value="orderTypeEnums.takeAway"
                v-model="checkoutProps.form.order_type"
                class="custom-radio-field"
              />
              <span class="custom-radio-span"></span>
            </div>
            <h3 class="db-field-label text-sm text-heading">سفري</h3>
          </label>

          <label
            ref="deliveryOrderLabel"
            @click="deliveryOrder"
            for="delivery"
            data-orderdelivery="#orderdelivery"
            type="button"
            class="!w-fit db-field-radio px-2.5 py-2 rounded-lg border border-[#F7F7FC] bg-[#F7F7FC]"
          >
            <div class="custom-radio sm">
              <input
                ref="deliveryOrderInput"
                type="radio"
                id="delivery"
                name="orderType"
                :value="orderTypeEnums.delivery"
                v-model="checkoutProps.form.order_type"
                class="custom-radio-field"
              />
              <span class="custom-radio-span"></span>
            </div>
            <h3 class="db-field-label text-sm text-heading">توصيل</h3>
          </label>
          <label
            ref="ReservationsLabel"
            @click="ReservationsOrder"
            for="Reservations"
            class="!w-fit db-field-radio px-2.5 py-2 rounded-lg border border-[#F7F7FC] bg-[#F7F7FC]"
          >
            <div class="custom-radio sm">
              <input
                ref="ReservationsInput"
                type="radio"
                id="Reservations"
                name="orderType"
                :value="orderTypeEnums.Reservations"
                v-model="checkoutProps.form.order_type"
                class="custom-radio-field"
              />
              <span class="custom-radio-span"></span>
            </div>
            <h3 class="db-field-label text-sm text-heading">
              {{ $t("Reservations") }}
            </h3>
          </label>
        </div>
        <div
          ref="deliveryOrderDiv"
          id="orderdelivery"
          class="h-auto hidden transition"
        >
          <div class="my-3 flex items-center gap-4">
            <div class="db-field flex-grow">
              <vue-select
                class="db-field-control text-sm rounded-lg appearance-none text-heading border-[#D9DBE9]"
                id="customerAddresses"
                :options="filteredCustomerAddresses"
                v-model="checkoutProps.form.address_id"
                @update:modelValue="updateSelectedAddress"
                value-by="id"
                label-by="labelAddress"
                :closeOnSelect="true"
                :searchable="true"
                :clearOnClose="true"
                :placeholder="$t('label.select_address')"
                :search-placeholder="$t('label.search_address')"
              />
            </div>
            <div
              @click.prevent="openAddressModal"
              class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center cursor-pointer"
            >
              <i class="fa-solid fa-circle-plus text-white"></i>
            </div>
          </div>
          <div
            v-if="selectedAddress?.id"
            class="p-3 rounded-lg w-full border border-[#D9DBE9] relative"
          >
            <div
              v-if="selectedAddress?.label"
              class="text-xs font-medium flex items-center gap-2 text-[#008BBA] mb-2"
            >
              <i class="icon-home"></i>
              <span class="font-medium leading-6 capitalize">{{
                selectedAddress?.label
              }}</span>
            </div>
            <div class="flex gap-2 text-xs font-normal items-center">
              <i class="icon-location1 mt-0.5 text-paragraph"></i>
              <span class="text-sm leading-6 text-heading">{{
                selectedAddress?.address
              }}</span>
            </div>
            <button
              @click="editAddressModal(selectedAddress)"
              data-modal="#addaddress"
              type="button"
              class="absolute top-2 right-2 group text-xs capitalize font-medium flex items-center rounded-3xl py-1.5 px-3 gap-1 text-[#00749B] bg-[#D6F5FF] transition hover:text-white hover:bg-[#00749B]"
            >
              <svg
                class="fill-[#00749B] transition group-hover:fill-white"
                width="10"
                height="10"
                viewBox="0 0 10 10"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M3.6882 9.01395L8.08139 3.33272C8.32015 3.02635 8.40504 2.67215 8.32546 2.31149C8.25648 1.98363 8.05486 1.67189 7.75243 1.43539L7.01493 0.849532C6.37293 0.33892 5.57706 0.392668 5.12076 0.978529L4.62732 1.61867C4.56365 1.69876 4.57957 1.81701 4.65916 1.88151C4.65916 1.88151 5.90602 2.88123 5.93255 2.90273C6.01744 2.98335 6.08111 3.09085 6.09703 3.21985C6.12356 3.47247 5.94846 3.70896 5.68848 3.74121C5.56645 3.75733 5.44972 3.71971 5.36483 3.64984L4.0543 2.60711C3.99063 2.55928 3.89512 2.56949 3.84207 2.63399L0.727569 6.66514C0.525949 6.91775 0.456974 7.24562 0.525949 7.56274L0.923883 9.28807C0.945106 9.37944 1.02469 9.44394 1.1202 9.44394L2.87111 9.42244C3.18945 9.41707 3.48658 9.27194 3.6882 9.01395ZM6.13984 8.47663H8.99489C9.27344 8.47663 9.5 8.70613 9.5 8.98831C9.5 9.27103 9.27344 9.5 8.99489 9.5H6.13984C5.86129 9.5 5.63473 9.27103 5.63473 8.98831C5.63473 8.70613 5.86129 8.47663 6.13984 8.47663Z"
                />
              </svg>
              <span>{{ $t("button.edit") }}</span>
            </button>
          </div>
        </div>

        <div ref="dineInDiv" id="dine" class="h-auto hidden transition">
          <div class="mt-3">
            <div class="db-field flex-grow">
              <vue-select
                class="db-field-control text-sm rounded-lg appearance-none text-heading border-[#D9DBE9]"
                id="diningtables"
                :options="diningtables"
                v-model="checkoutProps.form.dining_table_id"
                value-by="id"
                label-by="name"
                :closeOnSelect="true"
                :searchable="true"
                :clearOnClose="true"
                :placeholder="$t('label.select_table')"
                :search-placeholder="$t('label.search_table')"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <table class="w-full">
      <thead class="bg-[#FFEDF4]">
        <tr class="h-9">
          <th
            class="capitalize text-xs font-normal font-rubik text-left pl-3 text-heading"
          ></th>
          <th
            class="capitalize text-xs font-normal font-rubik text-left px-3 text-heading"
          >
            {{ $t("label.item") }}
          </th>
          <th
            class="capitalize text-xs font-normal font-rubik text-left px-3 text-heading"
          >
            {{ $t("label.qty") }}
          </th>
          <th
            class="capitalize text-xs font-normal font-rubik text-left px-3 text-heading"
          >
            {{ $t("label.price") }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(cart, index) in carts">
          <td class="pl-3 py-3 last:pr-3 align-top border-b border-[#EFF0F6]">
            <button @click.prevent="deleteCartItem(index)">
              <i class="lab lab-trash-line-2 font-fill-danger"></i>
            </button>
          </td>
          <td class="pl-3 py-3 last:pr-3 align-top border-b border-[#EFF0F6]">
            <h3 class="capitalize text-xs font-rubik text-[#2E2F38]">
              {{ cart.name }}
            </h3>
            <p v-if="Object.keys(cart.item_variations.variations).length !== 0">
              <span
                v-for="(variation, variationName) in cart.item_variations.names"
              >
                <span
                  class="capitalize text-[10px] leading-4 font-rubik text-heading"
                  >{{ variationName }}: &nbsp;</span
                >
                <span class="capitalize text-[10px] leading-4 font-rubik"
                  >{{ variation }}, &nbsp;</span
                >
              </span>
            </p>
            <ul
              v-if="
                cart.item_extras.extras.length > 0 || cart.instruction !== ''
              "
            >
              <li v-if="cart.item_extras.extras.length > 0" class="leading-4">
                <span
                  class="capitalize text-[10px] leading-4 font-rubik text-heading"
                >
                  {{ $t("label.extras") }}:
                </span>
                <p class="capitalize text-[10px] leading-4 font-rubik">
                  <span v-for="extra in cart.item_extras.names">
                    {{ extra }}, &nbsp;
                  </span>
                </p>
              </li>
              <li v-if="cart.instruction !== ''" class="leading-4">
                <span
                  class="capitalize text-[10px] leading-4 font-rubik text-heading"
                >
                  {{ $t("label.instruction") }}:
                </span>
                <span class="capitalize text-[10px] leading-4 font-rubik">
                  {{ cart.instruction }}
                </span>
              </li>
            </ul>
          </td>
          <td class="pl-3 py-3 last:pr-3 align-top border-b border-[#EFF0F6]">
            <div class="flex items-center indec-group">
              <button
                @click.prevent="cartQuantityDecrement(index)"
                :class="cart.quantity === 1 ? 'fa-trash-can' : 'fa-minus'"
                class="fa-solid text-[10px] w-[18px] h-[18px] leading-4 text-center rounded-full border transition text-primary border-primary hover:bg-primary hover:text-white indec-minus"
              ></button>
              <input
                v-on:keypress="onlyNumber($event)"
                v-on:keyup="cartQuantityUp(index, $event)"
                type="number"
                :value="cart.quantity"
                class="text-center w-7 text-xs font-semibold text-heading indec-value"
              />
              <button
                @click.prevent="cartQuantityIncrement(index)"
                class="fa-solid fa-plus text-[10px] w-[18px] h-[18px] leading4 text-center rounded-full border transition text-primary border-primary hover:bg-primary hover:text-white indec-plus"
              ></button>
            </div>
          </td>
          <td
            style="font-family: 'saudi_riyal'"
            class="pl-3 py-3 last:pr-3 align-top border-b border-[#EFF0F6] text-xs font-rubik text-heading"
          >
            {{
              currencyFormat(
                cart.total,
                setting.site_digit_after_decimal_point,
                setting.site_default_currency_symbol,
                setting.site_currency_position
              )
            }}
            &#xE900;
          </td>
        </tr>
      </tbody>
    </table>
    <div class="p-4">
      <div class="flex h-[38px]" v-if="carts.length > 0">
        <div class="db-field-down-arrow">
          <select
            v-model="discountType"
            class="w-[120px] h-full text-sm font-rubik rounded-tl rounded-bl appearance-none border pl-3 text-heading border-[#EFF0F6]"
          >
            <option :value="discountTypeEnum.PERCENTAGE">
              {{ $t("label.percentage") }}
            </option>
            <option :value="discountTypeEnum.FIXED">
              {{ $t("label.fixed") }}
            </option>
          </select>
        </div>
        <input
          v-on:keypress="floatNumber($event)"
          v-model="discount"
          type="text"
          :placeholder="$t('label.add_discount')"
          class="w-full h-full border-t border-b px-3 border-[#EFF0F6]"
        />
        <button
          @click.prevent="applyDiscount"
          type="submit"
          class="flex-shrink-0 w-16 h-full text-sm font-medium font-rubik capitalize ltr:rounded-tr-lg ltr:rounded-br-lg rtl:rounded-tl-lg rtl:rounded-bl-lg text-white bg-[#008BBA]"
        >
          {{ $t("button.apply") }}
        </button>
      </div>

      <ul class="flex flex-col gap-1.5 mb-4 mt-4">
        <li class="flex items-center justify-between">
          <span class="text-sm font-rubik capitalize leading-6 text-[#2E2F38]">
            {{ $t("label.sub_total") }}
          </span>
          <span
            style="font-family: 'saudi_riyal'"
            class="text-sm font-rubik capitalize leading-6 text-[#2E2F38]"
          >
            {{
              currencyFormat(
                subtotal,
                setting.site_digit_after_decimal_point,
                setting.site_default_currency_symbol,
                setting.site_currency_position
              )
            }}
            &#xE900;
          </span>
        </li>
        <li class="flex items-center justify-between">
          <span class="text-sm font-rubik capitalize leading-6">{{
            $t("label.discount")
          }}</span>
          <span
            style="font-family: 'saudi_riyal'"
            class="text-sm font-rubik capitalize leading-6"
            >{{
              currencyFormat(
                posDiscount,
                setting.site_digit_after_decimal_point,
                setting.site_default_currency_symbol,
                setting.site_currency_position
              )
            }}
            &#xE900;
          </span>
        </li>
        <li
          class="flex items-center justify-between"
          v-if="checkoutProps.form.delivery_charge"
        >
          <span class="text-sm font-rubik capitalize leading-6">{{
            $t("label.delivery_charge")
          }}</span>
          <span
            style="font-family: 'saudi_riyal'"
            class="text-sm font-rubik capitalize leading-6 font-medium text-[#1AB759]"
            >{{
              currencyFormat(
                checkoutProps.form.delivery_charge,
                setting.site_digit_after_decimal_point,
                setting.site_default_currency_symbol,
                setting.site_currency_position
              )
            }}
            &#xE900;
          </span>
        </li>
        <li class="flex items-center justify-between">
          <span
            class="text-sm font-medium font-rubik capitalize leading-6 text-[#2E2F38]"
          >
            {{ $t("label.total") }}
          </span>
          <span
            style="font-family: 'saudi_riyal'"
            class="text-sm font-medium font-rubik capitalize leading-6 text-[#2E2F38]"
          >
            {{
              currencyFormat(
                subtotal + checkoutProps.form.delivery_charge - posDiscount,
                setting.site_digit_after_decimal_point,
                setting.site_default_currency_symbol,
                setting.site_currency_position
              )
            }}
            &#xE900;
          </span>
        </li>
      </ul>
      <div
        class="flex items-center justify-center gap-6"
        v-if="carts.length > 0"
      >
        <button
          @click.prevent="resetCart"
          class="capitalize text-sm font-medium leading-6 font-rubik w-full text-center rounded-3xl py-2 text-white bg-[#FB4E4E]"
        >
          {{ $t("button.cancel") }}
        </button>
        <button
          @click.prevent="orderSubmit"
          class="capitalize text-sm font-medium leading-6 font-rubik w-full text-center rounded-3xl py-2 text-white bg-[#1AB759]"
        >
          {{ $t("button.order") }}
        </button>
      </div>
    </div>
  </div>

  <!--====================================
        ADD CUSTOMER MODAL PART START
=====================================-->
  <div id="addCustomer" class="modal">
    <div class="modal-dialog">
      <div class="modal-header pb-3 border-b border-[#D9DBE9]">
        <h3 class="capitalize font-medium">{{ $t("button.add_customer") }}</h3>
        <button
          @click="resetCustomer"
          class="modal-close fa-regular fa-circle-xmark"
        ></button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="saveCustomer">
          <div class="row mb-3">
            <div class="col-12 sm:col-6">
              <label class="db-field-title required">{{
                $t("label.name")
              }}</label>
              <input
                type="text"
                v-model="customerProps.form.name"
                v-bind:class="errors.name ? 'invalid' : ''"
                id="name"
                class="w-full h-12 text-sm rounded-lg border px-4 text-heading border-[#D9DBE9]"
              />
              <small class="db-field-alert" v-if="errors.name">{{
                errors.name[0]
              }}</small>
            </div>
            <div class="col-12 sm:col-6">
              <label for="phone" class="db-field-title">{{
                $t("label.phone")
              }}</label>
              <div
                :class="errors.phone ? 'invalid' : ''"
                class="w-full h-12 rounded-lg border px-4 flex items-center border-[#D9DBE9]"
              >
                <div class="w-fit flex-shrink-0 dropdown-group">
                  <button
                    type="button"
                    class="flex items-center gap-1 dropdown-btn"
                  >
                    {{ flag }}
                    <span class="whitespace-nowrap flex-shrink-0 text-xs">
                      {{ customerProps.form.country_code }}
                    </span>
                    <input
                      type="hidden"
                      v-model="customerProps.form.country_code"
                    />
                  </button>
                </div>
                <input
                  v-model="customerProps.form.phone"
                  v-on:keypress="phoneNumber($event)"
                  v-bind:class="errors.phone ? 'invalid' : ''"
                  type="text"
                  id="phone"
                  class="pl-2 text-sm w-full h-full"
                />
              </div>
              <small class="db-field-alert" v-if="errors.phone">
                {{ errors.phone[0] }}
              </small>
            </div>
            <div class="col-12 sm:col-6">
              <label class="db-field-title required">{{
                $t("label.email")
              }}</label>
              <input
                type="email"
                id="email"
                v-model="customerProps.form.email"
                v-bind:class="errors.email ? 'invalid' : ''"
                class="w-full h-12 text-sm rounded-lg border px-4 text-heading border-[#D9DBE9]"
              />
              <small class="db-field-alert" v-if="errors.email">{{
                errors.email[0]
              }}</small>
            </div>
            <div class="col-12 sm:col-6">
              <label for="password" class="db-field-title required">{{
                $t("label.password")
              }}</label>
              <input
                v-model="customerProps.form.password"
                v-bind:class="errors.password ? 'invalid' : ''"
                type="text"
                id="password"
                class="w-full h-12 text-sm rounded-lg border px-4 text-heading border-[#D9DBE9]"
                autocomplete="off"
              />
              <small class="db-field-alert" v-if="errors.password">{{
                errors.password[0]
              }}</small>
            </div>
            <input
              type="hidden"
              v-model="customerProps.form.password_confirmation"
            />
          </div>
          <button
            type="submit"
            class="rounded-3xl text-base py-3 px-3 font-medium w-full text-white bg-primary"
          >
            {{ $t("button.add_customer") }}
          </button>
        </form>
      </div>
    </div>
  </div>
  <!--====================================
          ADD CUSTOMER MODAL PART END
    =====================================-->

  <!--====================================
      PAYMENT MODAL PART START
  =====================================-->
  <PaymentComponent :props="checkoutProps" />
  <!--====================================
          PAYMENT MODAL PART END
      =====================================-->

  <!--====================================
      ADDRESS MODAL PART START
  =====================================-->
  <CreateCustomerAddressComponent :props="address" />
  <!--====================================
          ADDRESS MODAL PART END
      =====================================-->

  <button
    @click="openPosCart('pos-cart')"
    type="button"
    class="db-pos-cartBtn fixed md:hidden bottom-0 z-10 left-0 w-full h-14 py-4 text-center flex items-center justify-center shadow-xl-top gap-3 bg-primary"
  >
    <i class="lab lab-bag-2 lab-font-size-13 text-white"></i>
    <span
      style="font-family: 'saudi_riyal'"
      class="text-base font-medium font-rubik text-white"
    >
      {{ totalItems() }} {{ $t("label.items") }} -
      {{
        currencyFormat(
          subtotal - posDiscount,
          setting.site_digit_after_decimal_point,
          setting.site_default_currency_symbol,
          setting.site_currency_position
        )
      }}
      &#xE900;
    </span>
  </button>
  <!-- Reservations Modal -->
  <div id="reservationModal" class="modal">
    <div class="modal-dialog">
      <div class="modal-header pb-3 border-b border-[#D9DBE9]">
        <h3 class="capitalize font-medium">
          {{ $t("button.reservation_details") }}
        </h3>
        <button
          @click="closeReservationModal"
          class="modal-close fa-regular fa-circle-xmark"
        ></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-12">
            <label class="db-field-title required">{{
              $t("label.customer_name")
            }}</label>
            <div class="flex items-center w-full gap-4 mb-3">
              <div class="db-field flex-grow">
                <vue-select
                  class="db-field-control text-sm rounded-lg appearance-none text-heading border-[#D9DBE9]"
                  id="customer"
                  v-model="checkoutProps.form.customer_id"
                  :options="customers"
                  @update:modelValue="changingUser"
                  label-by="name"
                  value-by="id"
                  :closeOnSelect="true"
                  :searchable="true"
                  :clearOnClose="true"
                  :placeholder="$t('label.select_customer')"
                  :search-placeholder="$t('label.search_customer')"
                />
              </div>
              <!-- <div
                data-modal="#addCustomer"
                @click.prevent="addCustomers"
                class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center cursor-pointer"
              >
                <i class="fa-solid fa-circle-plus text-white"></i>
              </div> -->
            </div>
          </div>
          <div class="col-12 mt-3">
            <label class="db-field-title required">{{
              $t("label.reservationDate")
            }}</label>
            <input
              type="datetime-local"
              v-model="reservationDate"
              class="w-full h-12 text-sm rounded-lg border px-4 text-heading border-[#D9DBE9]"
              :min="minReservationDate"
              value=""
            />
          </div>
          <div class="col-12 mt-3">
            <label class="db-field-title">{{
              $t("label.reservationamount")
            }}</label>
            <input
              type="number"
              v-model="reservationamount"
              value=""
              class="w-full h-12 text-sm rounded-lg border px-4 text-heading border-[#D9DBE9]"
            />
          </div>
        </div>
        <button
          @click="confirmReservation"
          class="rounded-3xl text-base py-3 px-3 font-medium w-full text-white bg-primary mt-4"
        >
          {{ $t("button.confirm_reservation") }}
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import "vue3-carousel/dist/carousel.css";
import ItemComponent from "./ItemComponent";
import sourceEnum from "../../../enums/modules/sourceEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";
import isAdvanceOrderEnum from "../../../enums/modules/isAdvanceOrderEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import roleEnum from "../../../enums/modules/roleEnum";
import appService from "../../../services/appService";
import discountTypeEnum from "../../../enums/modules/discountTypeEnum";
import displayModeEnum from "../../../enums/modules/displayModeEnum";
import alertService from "../../../services/alertService";
import PaymentComponent from "./PaymentComponent";
import posPaymentMethodEnum from "../../../enums/modules/posPaymentMethodEnum";
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import focustrap from "bootstrap/js/src/util/focustrap";
import CustomerAddressCreateComponent from "../customers/address/CustomerAddressCreateComponent.vue";
import CreateCustomerAddressComponent from "./CreateCustomerAddressComponent.vue";
import labelEnum from "../../../enums/modules/labelEnum";

export default {
  name: "PosComponent",
  components: {
    CreateCustomerAddressComponent,
    CustomerAddressCreateComponent,
    LoadingComponent,
    ItemComponent,
    Swiper,
    SwiperSlide,
    PaymentComponent,
  },
  data() {
    return {
      loading: {
        isActive: false,
      },
      order: {},
      discount: null,
      checkoutProps: {
        form: {
          branch_id: null,
          subtotal: 0,
          token: "",
          customer_id: null,
          discount: 0,
          delivery_charge: 0,
          delivery_time: null,
          total: 0,
          order_type: orderTypeEnum.DINING_TABLE,
          is_advance_order: isAdvanceOrderEnum.NO,
          pos_payment_method: posPaymentMethodEnum.CASH,
          pos_payment_note: "",
          source: sourceEnum.POS,
          address_id: null,
          coupon_id: null,
          items: [],
          dining_table_id: null,
          pos_received_amount: null,
          Reservations: null,
          reservationDate: null,
          reservationamount: null,
          NetworkAmount: null,
        },
      },
      props: {
        search: {
          paginate: 0,
          order_column: "id",
          order_type: "asc",
          name: "",
          item_category_id: "",
          status: statusEnum.ACTIVE,
        },
      },
      categoryProps: {
        paginate: 0,
        order_column: "sort",
        order_type: "asc",
        status: statusEnum.ACTIVE,
      },

      statusEnum: statusEnum,
      discountTypeEnum: discountTypeEnum,
      discountType: discountTypeEnum.PERCENTAGE,
      posPaymentMethodEnum: posPaymentMethodEnum,
      reservationDate: null,
      reservationamount: null,
      customerProps: {
        form: {
          name: "",
          email: "",
          phone: "",
          password: "123456",
          password_confirmation: "123456",
          country_code: "",
          status: statusEnum.ACTIVE,
        },
        search: {
          paginate: 0,
          order_column: "id",
          order_type: "asc",
          status: statusEnum.ACTIVE,
        },
      },
      errors: {},

      flag: "",
      address: {
        form: {
          address: "",
          apartment: "",
          latitude: "",
          longitude: "",
          label: "",
          user_id: "",
        },
        Reservations: {
          form: {
            reservationDate: "",
            reservationamount: "",
            reservationDate: "",
            reservationamount: "",
          },
        },
        search: {
          order_column: "id",
          order_type: "desc",
        },
        status: false,
        switchLabel: "",
        isMap: false,
        vuex: false,
      },
      selectedAddress: {},
      orderTypeEnums: {
        dineIn: orderTypeEnum.DINING_TABLE,
        takeAway: orderTypeEnum.TAKEAWAY,
        delivery: orderTypeEnum.DELIVERY,
        Reservations: orderTypeEnum.Reservations,
      },
      location: {
        lat: null,
        lng: null,
      },
      clearAddresses: false,
    };
  },
  computed: {
    focustrap() {
      return focustrap;
    },
    setting: function () {
      return this.$store.getters["frontendSetting/lists"];
    },
    categories: function () {
      return this.$store.getters["posCategory/lists"];
    },
    items: function () {
      return this.$store.getters["item/lists"];
    },
    customers: function () {
      return this.$store.getters["user/lists"];
    },
    carts: function () {
      return this.$store.getters["posCart/lists"];
    },
    subtotal: function () {
      return this.$store.getters["posCart/subtotal"];
    },
    posDiscount: function () {
      return this.$store.getters["posCart/discount"];
    },
    direction: function () {
      return this.$store.getters["frontendLanguage/show"].display_mode ===
        displayModeEnum.RTL
        ? "rtl"
        : "ltr";
    },
    diningtables: function () {
      return this.$store.getters["diningTable/lists"];
    },
    filteredCustomerAddresses: function () {
      if (this.clearAddresses) {
        return [];
      }
      return this.customerAddresses;
    },
    customerAddresses: function () {
      return this.$store.getters["user/addressLists"];
    },

    formattedTotal() {
      return this.currencyFormat(
        this.subtotal - this.posDiscount,
        this.setting.site_digit_after_decimal_point,
        this.setting.site_default_currency_symbol,
        this.setting.site_currency_position
      );
    },
    minReservationDate() {
      const now = new Date();
      return now.toISOString().slice(0, 16);
    },
  },
  mounted() {
    this.closeSidebar();
    this.$refs.dineIn.click();
    this.itemCategories();
    this.itemList();
    try {
      this.loading.isActive = true;
      this.$store
        .dispatch("defaultAccess/show")
        .then((res) => {
          this.checkoutProps.form.branch_id = res.data.data.branch_id;
          this.$store
            .dispatch("frontendBranch/show", this.checkoutProps.form.branch_id)
            .then((res) => {
              this.location = {
                lat: res.data.data.latitude,
                lng: res.data.data.longitude,
              };
            })
            .catch();
        })
        .catch((err) => {
          this.loading.isActive = false;
        });

      this.loading.isActive = true;
      this.$store
        .dispatch("user/lists", {
          order_column: "id",
          order_type: "asc",
          status: statusEnum.ACTIVE,
        })
        .then((res) => {
          this.checkoutProps.form.customer_id = res.data.data[1].id;
          this.address.form.user_id = res.data.data[1].id;
          this.gettingUserAddress(this.checkoutProps.form.customer_id);
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });

      this.loading.isActive = true;
      this.$store
        .dispatch("company/lists")
        .then((res) => {
          this.company.name = res.data.data.company_name;
          this.company.email = res.data.data.company_email;
          this.company.phone = res.data.data.company_phone;
          this.company.address = res.data.data.company_address;
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
      this.loading.isActive = true;
      this.$store
        .dispatch("diningTable/lists", {
          order_column: "id",
          order_type: "desc",
          status: statusEnum.ACTIVE,
        })
        .then((res) => {
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });

      this.$store
        .dispatch("company/lists")
        .then((companyRes) => {
          this.$store
            .dispatch(
              "countryCode/show",
              companyRes.data.data.company_country_code
            )
            .then((res) => {
              if (this.customerProps.form.country_code === "") {
                this.customerProps.form.country_code =
                  res.data.data.calling_code;
                this.country_code = res.data.data.calling_code;
              }
              this.flag = res.data.data.flag_emoji;
            })
            .catch();
        })
        .catch();
    } catch (err) {
      this.loading.isActive = false;
    }
  },
  methods: {
    onlyNumber: function (e) {
      return appService.onlyNumber(e);
    },
    floatNumber: function (e) {
      return appService.floatNumber(e);
    },
    currencyFormat: function (amount, decimal, currency, position) {
      return appService.currencyFormat(amount, decimal, currency, position);
    },
    openPosCart: function (id) {
      return appService.openPosCart(id);
    },
    closePosCart: function (id) {
      return appService.closePosCart(id);
    },
    search: function () {
      this.itemList();
    },
    allCategory: function () {
      this.props.search.name = "";
      this.props.search.item_category_id = "";
      this.itemList();
    },
    closeSidebar: function () {
      this.$store.dispatch("globalState/set", { topSidebar: false });
      document?.querySelector(".db-sidebar")?.classList?.add("active");
      document?.querySelector(".db-main")?.classList?.add("expand");
    },
    itemCategories: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store
        .dispatch("posCategory/lists", this.categoryProps)
        .then((res) => {
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
    itemList: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store
        .dispatch("item/lists", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
    setCategory: function (id) {
      this.props.search.item_category_id = id;
      this.itemList();
    },
    cartQuantityUp: function (id, e) {
      if (e.target.value > 0) {
        this.$store
          .dispatch("posCart/quantity", { id: id, status: e.target.value })
          .then()
          .catch();
      }
    },
    cartQuantityIncrement: function (id) {
      this.$store
        .dispatch("posCart/quantity", { id: id, status: "increment" })
        .then()
        .catch();
    },
    cartQuantityDecrement: function (id) {
      this.$store
        .dispatch("posCart/quantity", { id: id, status: "decrement" })
        .then()
        .catch();
    },
    deleteCartItem: function (id) {
      this.$store
        .dispatch("posCart/deleteCartItem", { id: id, status: "decrement" })
        .then()
        .catch();
    },
    applyDiscount: function () {
      if (this.discountType == discountTypeEnum.FIXED) {
        if (this.subtotal < this.discount) {
          return alertService.error(
            this.$t("message.discount_fixed_error_message")
          );
        } else {
          this.checkoutProps.form.discount = parseFloat(+this.discount).toFixed(
            this.setting.site_digit_after_decimal_point
          );
          this.$store
            .dispatch("posCart/discount", this.checkoutProps.form.discount)
            .then()
            .catch();
        }
      } else {
        if (this.discount > 100) {
          return alertService.error(this.$t("message.discount_error_message"));
        } else {
          this.checkoutProps.form.discount = parseFloat(
            (this.subtotal * this.discount) / 100
          ).toFixed(this.setting.site_digit_after_decimal_point);
          this.$store
            .dispatch("posCart/discount", this.checkoutProps.form.discount)
            .then()
            .catch();
        }
      }
    },
    resetCart: function () {
      this.$store
        .dispatch("posCart/resetCart")
        .then((res) => {
          this.checkoutProps.form.token = "";
          this.checkoutProps.form.Reservations = null;
      this.checkoutProps.form.reservationDate = null;
      this.checkoutProps.form.reservationamount = null;
          this.Reservations = null;
          this.reservationDate = null;
          this.reservationamount = null;
        })
        .catch();
    },
    orderSubmit: function () {
      this.loading.isActive = true;
      this.checkoutProps.form.subtotal = this.subtotal;
      this.checkoutProps.form.total = parseFloat(
        this.subtotal +
          this.checkoutProps.form.delivery_charge -
          this.checkoutProps.form.discount
      ).toFixed(this.setting.site_digit_after_decimal_point);
      this.checkoutProps.form.items = [];
      _.forEach(this.carts, (item, index) => {
        let item_variations = [];
        if (Object.keys(item.item_variations.variations).length > 0) {
          _.forEach(item.item_variations.variations, (value, index) => {
            item_variations.push({
              id: value,
              item_id: item.item_id,
              item_attribute_id: index,
            });
          });
        }

        if (Object.keys(item.item_variations.names).length > 0) {
          let i = 0;
          _.forEach(item.item_variations.names, (value, index) => {
            item_variations[i].variation_name = index;
            item_variations[i].name = value;
            i++;
          });
        }

        let item_extras = [];
        if (item.item_extras.extras.length) {
          _.forEach(item.item_extras.extras, (value) => {
            item_extras.push({
              id: value,
              item_id: item.item_id,
            });
          });
        }

        if (item.item_extras.names.length) {
          let i = 0;
          _.forEach(item.item_extras.names, (value) => {
            item_extras[i].name = value;
            i++;
          });
        }

        this.checkoutProps.form.items.push({
          item_id: item.item_id,
          item_price: item.convert_price,
          branch_id: this.checkoutProps.form.branch_id,
          instruction: item.instruction,
          quantity: item.quantity,
          discount: item.discount,
          total_price: item.total,
          item_variation_total: item.item_variation_total,
          item_extra_total: item.item_extra_total,
          item_variations: item_variations,
          item_extras: item_extras,
        });
      });
      this.checkoutProps.form.items = JSON.stringify(
        this.checkoutProps.form.items
      );
      this.loading.isActive = false;
      if (this.checkoutProps.form.order_type !== orderTypeEnum.DINING_TABLE) {
        if (!this.checkoutProps.form.token) {
          //return alertService.error(this.$t("message.token_field_required"));
          this.checkoutProps.form.token = "1";
        }
      } else {
        if (this.checkoutProps.form.token) {
          this.checkoutProps.form.token = "1";
        }
      }

      if (
        this.checkoutProps.form.order_type === orderTypeEnum.DINING_TABLE &&
        !this.checkoutProps.form.dining_table_id
      ) {
        return alertService.error(this.$t("message.table_field_required"));
      }
      if (
        this.checkoutProps.form.order_type === orderTypeEnum.DELIVERY &&
        !this.checkoutProps.form.address_id
      ) {
        return alertService.error(this.$t("message.address_field_required"));
      }
      if (this.checkoutProps.form.order_type === orderTypeEnum.Reservations) {
        if (!this.checkoutProps.form.reservationDate) {
          return alertService.error(
            this.$t("message.reservation_date_required")
          );
        }
      }
      appService.modalShow("#orderpayment");
    },
    totalItems: function () {
      if (this.carts.length > 0) {
        let totalItem = 0;
        this.carts.forEach((cart) => {
          totalItem += cart.quantity;
        });
        return totalItem;
      }
    },
    phoneNumber(e) {
      return appService.phoneNumber(e);
    },
    addCustomers: function () {
      appService.modalShow("#addCustomer");
    },
    resetCustomer: function () {
      appService.modalHide("#addCustomer");
      this.$store.dispatch("user/reset").then().catch();
      this.errors = {};
      this.customerProps.form = {
        name: "",
        email: "",
        phone: "",
        password: "123456",
        password_confirmation: "123456",
        status: statusEnum.ACTIVE,
        country_code: this.country_code,
      };
    },
    saveCustomer: function () {
      try {
        this.loading.isActive = true;
        this.$store
          .dispatch("user/save", this.customerProps)
          .then((res) => {
            appService.modalHide("#addCustomer");
            alertService.successFlip(0, this.$t("menu.customers"));
            this.$store
              .dispatch("user/lists", {
                order_column: "id",
                order_type: "asc",
                status: statusEnum.ACTIVE,
                vuex: true,
              })
              .then((customerResponse) => {
                this.loading.isActive = false;
                this.checkoutProps.form.customer_id = res.data.data.id;
                this.address.form.user_id = res.data.data.id;
                this.selectedAddress = {};
                this.gettingUserAddress(this.checkoutProps.form.customer_id);
              })
              .catch((err) => {
                this.loading.isActive = false;
              });

            this.customerProps.form = {
              name: "",
              email: "",
              phone: "",
              password: "123456",
              password_confirmation: "123456",
              status: statusEnum.ACTIVE,
              country_code: this.country_code,
            };
            this.errors = {};
          })
          .catch((err) => {
            this.loading.isActive = false;
            this.errors = err.response.data.errors;
          });
      } catch (err) {
        this.loading.isActive = false;
        alertService.error(err);
      }
    },
    dineInOrder: function () {
      this.checkoutProps.form.address_id = null;
      this.selectedAddress = {};

      this.checkoutProps.form.delivery_charge = null;

      this.$refs.dineIn.classList.add("active");
      this.$refs.dineInDiv.classList.add("block");
      this.$refs.dineInDiv.classList.remove("hidden");

      this.$refs.takeAway.classList.remove("active");
      this.$refs.ReservationsLabel.classList.remove("active");
      this.$refs.deliveryOrderLabel.classList.remove("active");

      this.$refs.deliveryOrderDiv.classList.add("hidden");
      this.$refs.deliveryOrderDiv.classList.remove("block");
      this.checkoutProps.form.Reservations = null;
      this.checkoutProps.form.reservationDate = null;
      this.checkoutProps.form.reservationamount = null;
      this.$refs.ReservationsInput.checked = false;
      this.reservationDate = null;
      this.reservationamount = null;
    },
    takeAwayOrder: function () {
      this.checkoutProps.form.dining_table_id = null;
      this.checkoutProps.form.address_id = null;
      this.selectedAddress = {};
      this.checkoutProps.form.delivery_charge = null;
      this.$refs.takeAway.classList.add("active");
      this.$refs.dineIn.classList.remove("active");
      this.$refs.dineInDiv.classList.add("hidden");
      this.$refs.dineInDiv.classList.remove("block");

      this.$refs.deliveryOrderLabel.classList.remove("active");
      this.$refs.ReservationsLabel.classList.remove("active");
      this.$refs.deliveryOrderDiv.classList.add("hidden");
      this.$refs.deliveryOrderDiv.classList.remove("block");
      this.checkoutProps.form.Reservations = null;
      this.checkoutProps.form.reservationDate = null;
      this.checkoutProps.form.reservationamount = null;
      this.$refs.ReservationsInput.checked = false;
      this.reservationDate = null;
      this.reservationamount = null;
    },
    deliveryOrder: function () {
      this.checkoutProps.form.dining_table_id = null;
      this.$refs.ReservationsInput.checked = false;
      this.$refs.deliveryOrderLabel.classList.add("active");
      this.$refs.deliveryOrderDiv.classList.add("block");
      this.$refs.deliveryOrderDiv.classList.remove("hidden");
      this.$refs.dineIn.classList.remove("active");
      this.$refs.dineInDiv.classList.add("hidden");
      this.$refs.dineInDiv.classList.remove("block");
      this.$refs.takeAway.classList.remove("active");
      this.$refs.ReservationsLabel.classList.remove("active");
      this.checkoutProps.form.reservationDate = null;
      this.checkoutProps.form.reservationamount = null;
      this.checkoutProps.form.Reservations = null;
      this.reservationDate = null;
      this.reservationamount = null;
    },
    Reservations_order: function () {
      this.checkoutProps.form.dining_table_id = null;

      this.$refs.ReservationsLabel.classList.add("active");
      this.$refs.deliveryOrderDiv.classList.add("block");
      this.$refs.deliveryOrderDiv.classList.remove("hidden");
      this.$refs.dineIn.classList.remove("active");
      this.$refs.dineInDiv.classList.add("hidden");
      this.$refs.dineInDiv.classList.remove("block");
      this.$refs.takeAway.classList.remove("active");
      this.$refs.deliveryOrderLabel.classList.remove("active");
    },
    gettingUserAddress: function (userId) {
      this.$store
        .dispatch("user/addressLists", {
          id: userId,
          order_column: "id",
          order_type: "desc",
        })
        .then()
        .catch();
    },
    openAddressModal: function () {
      this.address.isMap = true;
      appService.modalShow("#addressModal");
    },
    editAddressModal: function (address) {
      appService.modalShow("#addressModal");
      this.loading.isActive = true;
      this.$store
        .dispatch("user/editAddress", address.id)
        .then((res) => {
          this.loading.isActive = false;
          this.address.isMap = true;
          this.address.form = {
            address: address.address,
            apartment: address.apartment,
            latitude: address.latitude,
            longitude: address.longitude,
            label: address.label,
            user_id: address.user_id,
          };
          this.checkoutProps.form.address_id = null;
          this.checkoutProps.form.Reservations = null;
          this.reservationDate = null;
          this.reservationamount = null;
          this.checkoutProps.form.reservationDate = null;
          this.checkoutProps.form.reservationamount = null;
          this.selectedAddress = {};
          if (this.address.form.label === this.$t("label.home")) {
            this.address.status = false;
            this.address.switchLabel = labelEnum.HOME;
          } else if (this.address.form.label === this.$t("label.work")) {
            this.address.status = false;
            this.address.switchLabel = labelEnum.WORK;
          } else {
            this.address.status = true;
            this.address.switchLabel = labelEnum.OTHER;
          }
        })
        .catch((err) => {
          alertService.error(err.response.data.message);
        });
    },
    changingUser: function () {
      if (this.checkoutProps.form.customer_id !== null) {
        this.clearAddresses = false;
        this.gettingUserAddress(this.checkoutProps.form.customer_id);
      } else {
        this.clearAddresses = true;
      }
      this.address.form.user_id = this.checkoutProps.form.customer_id;
      this.selectedAddress = {};
      this.checkoutProps.form.delivery_charge = null;
    },
    updateSelectedAddress: function () {
      const address = this.customerAddresses.find(
        (item) => item.id === this.checkoutProps.form.address_id
      );
      this.selectedAddress = address || {};
      this.deliveryChargeCalculation();
      if (this.checkoutProps.form.address_id === null) {
        this.checkoutProps.form.delivery_charge = null;
      }
    },
    deliveryChargeCalculation: function () {
      if (this.checkoutProps.form.order_type === orderTypeEnum.DELIVERY) {
        if (
          typeof this.selectedAddress.latitude !== "undefined" &&
          this.selectedAddress.latitude !== "" &&
          typeof this.selectedAddress.longitude !== "undefined" &&
          this.selectedAddress.longitude !== "" &&
          typeof this.location.lat !== "undefined" &&
          this.location.lat !== "" &&
          typeof this.location.lng !== "undefined" &&
          this.location.lng !== ""
        ) {
          const distance = appService.distance(
            parseFloat(this.selectedAddress.latitude),
            parseFloat(this.selectedAddress.longitude),
            parseFloat(this.location.lat),
            parseFloat(this.location.lng)
          );
          if (distance > this.setting.order_setup_free_delivery_kilometer) {
            let extraDistance =
              distance -
              parseFloat(this.setting.order_setup_free_delivery_kilometer);
            this.checkoutProps.form.delivery_charge =
              extraDistance *
                parseFloat(this.setting.order_setup_charge_per_kilo) +
              parseFloat(this.setting.order_setup_basic_delivery_charge);
          } else {
            this.checkoutProps.form.delivery_charge = parseFloat(
              this.setting.order_setup_basic_delivery_charge
            );
          }
        }
      }
    },
    ReservationsOrder() {
      this.Reservations_order();
      this.checkoutProps.form.Reservations = null;
      this.checkoutProps.form.reservationDate = null;
      this.checkoutProps.form.reservationamount = null;
      this.reservationDate = null;
      this.reservationamount = null;
      if (!this.checkoutProps.form.customer_id) {
        alertService.error(this.$t("message.select_customer_first"));
        this.$refs.ReservationsInput.checked = false;
        this.checkoutProps.form.order_type = orderTypeEnum.DINING_TABLE;
        this.$refs.dineInInput.checked = true;
        this.dineInOrder();
        return;
      }

      appService.modalShow("#reservationModal");
    },

    closeReservationModal() {
      appService.modalHide("#reservationModal");
      this.$refs.ReservationsInput.checked = false;
      this.checkoutProps.form.order_type = orderTypeEnum.DINING_TABLE;
      this.$refs.dineInInput.checked = true;
      this.dineInOrder();
      this.checkoutProps.form.Reservations = null;
      this.checkoutProps.form.reservationDate = null;
      this.checkoutProps.form.reservationamount = null;
      this.reservationDate = null;
      this.reservationamount = null;
    },

    confirmReservation() {
      if (!this.reservationDate) {
        alertService.error(this.$t("message.reservation_date_required"));
        return;
      }
      this.$refs.ReservationsInput.checked = true;

      this.checkoutProps.form.order_type = orderTypeEnum.Reservations;
      // Set reservation date in form
      this.checkoutProps.form.Reservations = [
        "reservationDate" >= this.reservationDate,
        "reservationamount" >= this.reservationamount,
      ];
      this.checkoutProps.form.reservationDate = this.reservationDate;
      this.checkoutProps.form.reservationamount = this.reservationamount;
      appService.modalHide("#reservationModal");
      this.Reservations_order();
      // Continue with order submission
      //   this.orderSubmit();
    },
  },
  watch: {
    "customerProps.form.password"(newValue) {
      this.customerProps.form.password_confirmation = newValue;
    },
    carts: {
      handler(newCarts) {
        if (!newCarts || newCarts.length === 0) {
          this.discount = null;
          this.discountType = discountTypeEnum.PERCENTAGE;
          this.$nextTick(() => {
            if (this.$refs.dineIn) {
              this.$refs.dineIn.click();
              if (this.customers.length > 1) {
                this.checkoutProps.form.customer_id = this.customers[1].id;
                this.address.form.user_id = this.customers[1].id;
                this.gettingUserAddress(this.checkoutProps.form.customer_id);
              }
            }
          });
        }
      },
      deep: true,
      immediate: true,
    },
  },
};
</script>
