import PosDashboard from '../../components/admin/pos/PosDashboard.vue';
import PosSessionList from '../../components/admin/pos/sessions/PosSessionList.vue';
import PosSessionStart from '../../components/admin/pos/sessions/PosSessionStart.vue';
import PosSessionActive from '../../components/admin/pos/sessions/PosSessionActive.vue';
import PosSessionSummary from '../../components/admin/pos/sessions/PosSessionSummary.vue';
import PosCashMovement from '../components/admin/pos/PosCashMovement.vue';
import ShiftTypeList from '../../components/admin/pos/shiftTypes/ShiftTypeList.vue';
import ShiftTypeCreate from '../../components/admin/pos/shiftTypes/ShiftTypeCreate.vue';
import ShiftTypeEdit from '../../components/admin/pos/shiftTypes/ShiftTypeEdit.vue';
import PosApprovalList from '../components/admin/pos/approvals/PosApprovalList.vue';
import PosApprovalCreate from '../../components/admin/pos/approvals/PosApprovalCreate.vue';
import PosSessionReports from '../../components/admin/pos/reports/PosSessionReports.vue';

export default [
    {
        path: '/pos',
        name: 'pos.dashboard',
        component: PosDashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/pos/sessions',
        name: 'pos.sessions',
        component: PosSessionList,
        meta: { requiresAuth: true }
    },
    {
        path: '/pos/sessions/start',
        name: 'pos.start',
        component: PosSessionStart,
        meta: { requiresAuth: true }
    },
    {
        path: '/pos/sessions/active',
        name: 'pos.active',
        component: PosSessionActive,
        meta: { requiresAuth: true }
    },
    {
        path: '/pos/sessions/summary/:id',
        name: 'pos.summary',
        component: PosSessionSummary,
        meta: { requiresAuth: true }
    },
    {
        path: '/pos/reports/sessions',
        name: 'pos.reports.sessions',
        component: PosSessionReports,
        meta: { requiresAuth: true }
    },
    {
        path: '/pos/cash-movement',
        name: 'pos.cashMovement',
        component: PosCashMovement,
        meta: { requiresAuth: true }
    },
    {
        path: '/pos/shift-types',
        name: 'shift-types.index',
        component: ShiftTypeList,
        meta: { requiresAuth: true }
    },
    {
        path: '/pos/shift-types/create',
        name: 'shift-types.create',
        component: ShiftTypeCreate,
        meta: { requiresAuth: true }
    },
    {
        path: '/pos/shift-types/edit/:id',
        name: 'shift-types.edit',
        component: ShiftTypeEdit,
        meta: { requiresAuth: true }
    },
    {
        path: '/pos/approvals',
        name: 'pos.approvals',
        component: PosApprovalList,
        meta: { requiresAuth: true }
    },
    {
        path: '/pos/approvals/create/:sessionId',
        name: 'pos.approvals.create',
        component: PosApprovalCreate,
        meta: { requiresAuth: true }
    }
];
