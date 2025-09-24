<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Badge, type BadgeVariants } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { testPage } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

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

interface OfferFilters {
    search?: string | null;
}

const props = defineProps<{
    offers: Offer[];
    filters?: OfferFilters;
}>();

const offers = computed(() => props.offers ?? []);

const searchQuery = ref(props.filters?.search ?? '');

watch(
    () => props.filters?.search,
    (value) => {
        searchQuery.value = value ?? '';
    },
);

const hasSearch = computed(() => searchQuery.value.trim().length > 0);

const visitOffers = (params: Record<string, unknown> = {}) => {
    router.get(testPage(), params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        only: ['offers', 'filters'],
    });
};

const applySearch = () => {
    const query = searchQuery.value.trim();

    searchQuery.value = query;

    visitOffers(query ? { search: query } : {});
};

const resetSearch = () => {
    if (!hasSearch.value && !(props.filters?.search ?? '')) {
        return;
    }

    searchQuery.value = '';

    visitOffers();
};

type CreateOfferForm = {
    title: string;
    description: string;
    price: string;
    currency: string;
    status: Offer['status'];
    start_date: string;
    end_date: string | null;
};

const statusOptions: Array<{ value: Offer['status']; label: string }> = [
    { value: 'draft', label: 'Rascunho' },
    { value: 'active', label: 'Ativa' },
    { value: 'expired', label: 'Expirada' },
];

const currencyOptions: Array<{ value: string; label: string }> = [
    { value: 'BRL', label: 'Real (BRL)' },
    { value: 'USD', label: 'Dólar (USD)' },
    { value: 'EUR', label: 'Euro (EUR)' },
];

const createOfferForm = useForm<CreateOfferForm>({
    title: '',
    description: '',
    price: '',
    currency: 'BRL',
    status: 'draft',
    start_date: '',
    end_date: '',
});

const deletingOfferId = ref<number | null>(null);

const handleCreateOffer = () => {
    createOfferForm
        .transform((data) => ({
            ...data,
            end_date: data.end_date || null,
        }))
        .post('/offers', {
            preserveScroll: true,
            onSuccess: () => {
                createOfferForm.reset('title', 'description', 'price', 'start_date', 'end_date');

                if (hasSearch.value) {
                    visitOffers({ search: searchQuery.value });
                }
            },
        });
};

const deleteOffer = (offer: Offer) => {
    if (!confirm(`Deseja realmente excluir a oferta "${offer.title}"?`)) {
        return;
    }

    deletingOfferId.value = offer.id;

    router.delete(`/offers/${offer.id}`, {
        preserveScroll: true,
        data: hasSearch.value ? { search: searchQuery.value } : {},
        onSuccess: () => {
            if (hasSearch.value) {
                visitOffers({ search: searchQuery.value });
            }
        },
        onFinish: () => {
            deletingOfferId.value = null;
        },
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Ofertas',
        href: testPage().url,
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
                <div class="border-b border-border p-4">
                    <form
                        class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                        @submit.prevent="applySearch"
                    >
                        <Input
                            v-model="searchQuery"
                            type="search"
                            name="search"
                            placeholder="Buscar ofertas"
                            class="w-full sm:max-w-md"
                            autocomplete="off"
                        />
                        <div class="flex items-center gap-2">
                            <Button type="submit">Buscar</Button>
                            <Button
                                v-if="hasSearch"
                                type="button"
                                variant="ghost"
                                @click="resetSearch"
                            >
                                Limpar
                            </Button>
                        </div>
                    </form>
                </div>
                <div class="border-b border-border p-4">
                    <form class="space-y-4" @submit.prevent="handleCreateOffer">
                        <div class="space-y-1">
                            <h2 class="text-lg font-semibold text-foreground">Cadastrar nova oferta</h2>
                            <p class="text-sm text-muted-foreground">
                                Preencha os dados abaixo para adicionar uma nova oferta ao catálogo.
                            </p>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="grid gap-2 md:col-span-2">
                                <Label for="title">Título</Label>
                                <Input
                                    id="title"
                                    v-model="createOfferForm.title"
                                    name="title"
                                    required
                                    autocomplete="off"
                                    placeholder="Nome da oferta"
                                    :aria-invalid="createOfferForm.errors.title ? true : undefined"
                                />
                                <InputError :message="createOfferForm.errors.title" />
                            </div>

                            <div class="grid gap-2 md:col-span-2">
                                <Label for="description">Descrição</Label>
                                <textarea
                                    id="description"
                                    v-model="createOfferForm.description"
                                    name="description"
                                    required
                                    rows="4"
                                    placeholder="Detalhes da oferta"
                                    :aria-invalid="createOfferForm.errors.description ? true : undefined"
                                    class="file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input min-h-[120px] w-full rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs outline-none transition-[color,box-shadow] focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40"
                                />
                                <InputError :message="createOfferForm.errors.description" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="price">Preço</Label>
                                <Input
                                    id="price"
                                    v-model="createOfferForm.price"
                                    name="price"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    required
                                    placeholder="0,00"
                                    :aria-invalid="createOfferForm.errors.price ? true : undefined"
                                />
                                <InputError :message="createOfferForm.errors.price" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="currency">Moeda</Label>
                                <select
                                    id="currency"
                                    v-model="createOfferForm.currency"
                                    name="currency"
                                    required
                                    class="file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs outline-none transition-[color,box-shadow] focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 md:text-sm"
                                    :aria-invalid="createOfferForm.errors.currency ? true : undefined"
                                >
                                    <option v-for="currency in currencyOptions" :key="currency.value" :value="currency.value">
                                        {{ currency.label }}
                                    </option>
                                </select>
                                <InputError :message="createOfferForm.errors.currency" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="status">Status</Label>
                                <select
                                    id="status"
                                    v-model="createOfferForm.status"
                                    name="status"
                                    required
                                    class="file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs outline-none transition-[color,box-shadow] focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 md:text-sm"
                                    :aria-invalid="createOfferForm.errors.status ? true : undefined"
                                >
                                    <option v-for="status in statusOptions" :key="status.value" :value="status.value">
                                        {{ status.label }}
                                    </option>
                                </select>
                                <InputError :message="createOfferForm.errors.status" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="start_date">Início</Label>
                                <Input
                                    id="start_date"
                                    v-model="createOfferForm.start_date"
                                    name="start_date"
                                    type="datetime-local"
                                    required
                                    :aria-invalid="createOfferForm.errors.start_date ? true : undefined"
                                />
                                <InputError :message="createOfferForm.errors.start_date" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="end_date">Fim</Label>
                                <Input
                                    id="end_date"
                                    v-model="createOfferForm.end_date"
                                    name="end_date"
                                    type="datetime-local"
                                    :aria-invalid="createOfferForm.errors.end_date ? true : undefined"
                                />
                                <InputError :message="createOfferForm.errors.end_date" />
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <Button type="submit" :disabled="createOfferForm.processing">
                                Cadastrar oferta
                            </Button>
                            <p
                                v-if="createOfferForm.recentlySuccessful"
                                class="text-sm text-muted-foreground"
                            >
                                Oferta cadastrada com sucesso.
                            </p>
                        </div>
                    </form>
                </div>
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
                                <th scope="col" class="px-4 py-3 font-semibold text-muted-foreground text-right">
                                    Ações
                                </th>
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
                                <td class="px-4 py-3 text-right">
                                    <Button
                                        type="button"
                                        variant="destructive"
                                        size="sm"
                                        :disabled="deletingOfferId === offer.id"
                                        @click="deleteOffer(offer)"
                                    >
                                        {{ deletingOfferId === offer.id ? 'Removendo...' : 'Excluir' }}
                                    </Button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td class="px-4 py-6 text-center text-muted-foreground" colspan="7">
                                    {{
                                        hasSearch
                                            ? 'Nenhuma oferta encontrada para a busca atual.'
                                            : 'Nenhuma oferta cadastrada no momento.'
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
