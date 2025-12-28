<template>
    <LoadingComponent :props="loading" />

    <div class="col-12">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 mb-5">
            <button type="button"
                @click="handleTab($event, '#information', '.profile-tabBtn', '.profile-tabDiv', 'active')"
                class="profile-tabBtn active w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                <i class="lab lab-information"></i>
                <span class="capitalize text-sm">{{ $t("label.information") }}</span>
            </button>
            <button type="button" @click="handleTab($event, '#map', '.profile-tabBtn', '.profile-tabDiv', 'active')"
                class="profile-tabBtn w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                <i class="lab lab-location"></i>
                <span class="capitalize text-sm">{{ $t('label.map') }}</span>
            </button>
        </div>
        <div id="information" class="profile-tabDiv active">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t("label.basic_info") }}</h3>
                </div>
                <div class="db-card-body">
                    <div class="row py-2">
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.name") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ branch.name }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.email") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ branch.email }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.latitude") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ branch.latitude }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.longitude") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ branch.longitude }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.phone") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ branch.phone }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.city") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ branch.city }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.state") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ branch.state }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.zip_code") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ branch.zip_code }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.status") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">
                                    <span :class="statusClass(branch.status)" class="db-badge">{{
                                        enums.statusEnumArray[branch.status] }}</span>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-text mt-2 w-full">
                                    <span class="mt-2 db-list-item-title">{{ $t('label.address') }}</span><br />
                                    <span class="mt-2">{{ branch.address }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="profile-tabDiv" id="map">
            <div class="row py-2">
                <div class="col-12 sm:col-12 !py-1.5">
                    <div class="db-list-item p-0">
                        <div id="the-google-map-for-zone" class="w-full h-[300px] rounded-xl"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 sm:col-12 py-5 text-right">
                    <button class="db-btn py-2 text-white bg-gray-600" @click="clearPolygon">
                        <i class="lab lab-cross-line-2"></i>
                        <span>{{ $t('button.clear') }}</span>
                    </button>
                    <button @click="saveZone()" type="button" class="ml-2 db-btn py-2 text-white bg-primary">
                        <i class="lab lab-save"></i>
                        <span>{{ $t("button.save") }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import statusEnum from "../../../../enums/modules/statusEnum";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import { Loader } from "google-maps";
import _ from "lodash";
import ENV from '../../../../config/env'

const options = { libraries: ["places", "geometry", "drawing"] };
const loader = new Loader(ENV.GOOGLE_MAP_KEY, options);

export default {
    name: "BranchShowComponent",
    components: {
        LoadingComponent,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },

            currentLocation: {
                lat: null,
                lng: null,
            },
            coordinatesArray: [],
            polygons: [],
            polygonsCoordinates: [],
            drawingPolygon: null,
            drawingManager: null,
            errors: {},
        };
    },
    computed: {
        branch: function () {
            return this.$store.getters["branch/show"];
        },
    },
    mounted: async function () {
        this.loading.isActive = true;
        await this.$store.dispatch("branch/show", this.$route.params.id).then(res => {
            if ((this.branch.latitude === null || this.branch.latitude === "") && (this.branch.longitude === null || this.branch.longitude === "")) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.currentLocation = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };
                            this.mainMap(this.currentLocation, res.data.data);
                        }, () => {
                            alert('The Geolocation service failed.');
                        }
                    );
                } else {
                    alert("Your browser doesn't support geolocation.");
                }
            } else {
                this.currentLocation.lat = parseFloat(this.branch.latitude);
                this.currentLocation.lng = parseFloat(this.branch.longitude);
                this.mainMap(this.currentLocation, res.data.data);
            }

        }).catch();
        this.loading.isActive = false;
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        handleTab: function (event, targetID, targetButton, targetDiv, activeClass) {
            return appService.handleTab(event, targetID, targetButton, targetDiv, activeClass);
        },
        mainMap: async function (location, data) {
            const google = await loader.load();

            const branchLocation = {
                lat: location.lat,
                lng: location.lng
            };

            const map = new google.maps.Map(document.getElementById("the-google-map-for-zone"), {
                center: branchLocation,
                zoom: 15,
                scrollwheel: false,
            });

            let markers = [];
            const marker = new google.maps.Marker({
                position: location,
                map: map
            });
            markers.push(marker);

            this.drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [google.maps.drawing.OverlayType.POLYGON]
                }
            });

            let polygon = null;
            if (data.zone) {
                this.coordinatesArray = JSON.parse(JSON.parse(data.zone));
                polygon = new google.maps.Polygon({
                    paths: this.coordinatesArray,
                    map: map,
                    editable: true
                });

                this.polygons.push(polygon);
                this.polygonsCoordinates.push(this.coordinatesArray);
            }

            google.maps.event.addListener(this.drawingManager, 'overlaycomplete', (event) => {
                if (data.zone) {
                    polygon.setMap(null);
                }

                if (event.type === 'polygon') {
                    if (this.drawingPolygon) {
                        this.drawingPolygon.setMap(null);
                        this.drawingPolygon = null;
                    }

                    this.drawingPolygon = event.overlay;
                    this.polygons.push(event.overlay);
                    this.drawingManager.setDrawingMode(null);
                    if (event.overlay.getPath().getLength() > 2) {
                        this.coordinatesArray = JSON.parse(JSON.stringify(event.overlay.getPath().getArray()));
                        this.polygonsCoordinates.push(this.coordinatesArray);
                    }
                }
            });

            this.drawingManager.setMap(map);
        },
        clearPolygon: function () {
            for (let i = 0; i < this.polygons.length; i++) {
                this.polygons[i].setMap(null);
            }
            this.polygons = [];
            this.polygonsCoordinates = [];
            if (this.drawingPolygon) {
                this.drawingPolygon.setMap(null);
                this.drawingPolygon = null;
            }
            this.coordinatesArray = [];
        },
        saveZone: function () {
            if (this.coordinatesArray.length < 1) {
                alertService.info(this.$t("message.zone_required"));
                return;
            }
            let payload = {
                id: this.branch.id,
                zone: { zone: JSON.stringify(this.coordinatesArray) }
            }
            this.loading.isActive = true;
            this.$store.dispatch("branch/saveZone", payload).then((res) => {
                alertService.success(this.$t("message.zone_update_successfully"));
                this.loading.isActive = false;
            }).catch((err) => {
                alertService.error(err);
                this.loading.isActive = false;
            });
        }
    },
};
</script>