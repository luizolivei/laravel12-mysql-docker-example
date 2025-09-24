<script setup lang="ts">
import { Badge, type BadgeVariants } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Offer {
    id: number;
    title: string;
    description: string;
    price: number;
    currency: string;
    status: 'active' | 'expired' | 'draft';
    start_date: string | null;
    end_date: string | null;
    created_at: string | null;
    updated_at: string | null;
}

const props = defineProps<{
    offers: Offer[];
}>();

const offers = computed(() => props.offers ?? []);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Ofertas',
    },
];

type BadgeVariant = NonNullable<BadgeVariants['variant']>;

const statusVariantMap: Record<Offer['status'], BadgeVariant> = {
    active: 'default',
    expired: 'destructive',
    draft: 'secondary',
};

const statusLabelMap: Record<Offer['status'], string> = {
    active: 'Ativa',
    expired: 'Expirada',
    draft: 'Rascunho',
};

const getStatusVariant = (status: Offer['status']): BadgeVariant =>
    statusVariantMap[status] ?? 'outline';

const getStatusLabel = (status: Offer['status']) =>
    statusLabelMap[status] ?? status;

const formatCurrency = (value: number | string, currency: Offer['currency']) =>
    new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency,
    }).format(Number(value));

const normalizeDate = (value: string) =>
    value.includes('T') ? value : value.replace(' ', 'T');

const formatDate = (value?: string | null) => {
    if (!value) {
        return '—';
    }

    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(new Date(normalizeDate(value)));
};
</script>

<template>
    <Head title="Ofertas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <section class="space-y-2">
                <h1 class="text-3xl font-semibold text-foreground">Ofertas</h1>
                <p class="text-sm text-muted-foreground">
                    Confira a listagem das ofertas cadastradas com seus principais detalhes.
                </p>
            </section>

            <div
                class="flex-1 overflow-hidden rounded-xl border border-border bg-card shadow-sm dark:border-sidebar-border"
            >
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border text-left text-sm">
                        <thead class="bg-muted">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-semibold text-muted-foreground">ID</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-muted-foreground">Título</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-muted-foreground">
                                    Descrição
                                </th>
                                <th scope="col" class="px-4 py-3 font-semibold text-muted-foreground">Preço</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-muted-foreground">Status</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-muted-foreground">Período</th>
                            </tr>
                        </thead>
                        <tbody v-if="offers.length > 0" class="divide-y divide-border">
                            <tr
                                v-for="offer in offers"
                                :key="offer.id"
                                class="transition-colors hover:bg-muted"
                            >
                                <td class="px-4 py-3 font-medium text-foreground">{{ offer.id }}</td>
                                <td class="px-4 py-3">
                                    <div class="space-y-1">
                                        <p class="text-base font-medium leading-tight text-foreground">
                                            {{ offer.title }}
                                        </p>
                                        <p class="text-xs text-muted-foreground">
                                            Criada em {{ formatDate(offer.created_at) }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-muted-foreground">
                                    {{ offer.description }}
                                </td>
                                <td class="px-4 py-3 font-medium text-foreground">
                                    {{ formatCurrency(offer.price, offer.currency) }}
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :variant="getStatusVariant(offer.status)" class="capitalize">
                                        {{ getStatusLabel(offer.status) }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3 text-sm text-foreground">
                                    <div class="flex flex-col gap-1">
                                        <span>Início: {{ formatDate(offer.start_date) }}</span>
                                        <span class="text-xs text-muted-foreground">
                                            Fim: {{ formatDate(offer.end_date) }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td class="px-4 py-6 text-center text-muted-foreground" colspan="6">
                                    Nenhuma oferta cadastrada no momento.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
