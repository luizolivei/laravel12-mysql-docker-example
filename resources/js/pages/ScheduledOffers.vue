<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, reactive, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Category {
    id: number;
    name: string;
    active: boolean;
}

interface ScheduledOffer {
    id: number;
    category_id: number;
    title: string;
    description: string;
    price: number;
    currency: string;
    status: 'draft' | 'active' | 'expired';
    active: boolean;
    start_date: string | null;
    end_date: string | null;
    scheduled_for: string | null;
    processed_at: string | null;
}

const props = defineProps<{
    categories: Category[];
    scheduledOffers: ScheduledOffer[];
}>();

const scheduleUrl = '/agendamentos';

const breadcrumbs = reactive<BreadcrumbItem[]>([
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Agendamentos', href: scheduleUrl },
]);

const categories = computed(() => props.categories ?? []);
const scheduledOffers = computed(() => props.scheduledOffers ?? []);

const statusOptions: Array<{ value: ScheduledOffer['status']; label: string }> = [
    { value: 'draft', label: 'Rascunho' },
    { value: 'active', label: 'Ativa' },
    { value: 'expired', label: 'Expirada' },
];

const currencyOptions: Array<{ value: string; label: string }> = [
    { value: 'BRL', label: 'Real (BRL)' },
    { value: 'USD', label: 'Dólar (USD)' },
    { value: 'EUR', label: 'Euro (EUR)' },
];

const formatDateForInput = (date: Date) => {
    const pad = (value: number) => value.toString().padStart(2, '0');

    return [
        `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`,
        `${pad(date.getHours())}:${pad(date.getMinutes())}`,
    ].join('T');
};

const getDefaultScheduledFor = () => formatDateForInput(new Date(Date.now() + 60 * 60 * 1000));

type ScheduleForm = {
    category_id: string;
    title: string;
    description: string;
    price: string;
    currency: string;
    status: ScheduledOffer['status'];
    start_date: string;
    end_date: string;
    scheduled_for: string;
};

const scheduleForm = useForm<ScheduleForm>({
    category_id: categories.value.length > 0 ? String(categories.value[0].id) : '',
    title: '',
    description: '',
    price: '',
    currency: 'BRL',
    status: 'draft' as ScheduledOffer['status'],
    start_date: '',
    end_date: '',
    scheduled_for: getDefaultScheduledFor(),
});

watch(
    () => categories.value,
    (value) => {
        if (value.length > 0 && !scheduleForm.category_id) {
            scheduleForm.category_id = String(value[0].id);
        }
    },
    { immediate: true },
);

const submit = () => {
    scheduleForm
        .transform((data) => ({
            ...data,
            category_id: data.category_id ? Number(data.category_id) : null,
            end_date: data.end_date || null,
        }))
        .post(scheduleUrl, {
            preserveScroll: true,
            onSuccess: () => {
                scheduleForm.reset('title', 'description', 'price', 'start_date', 'end_date');
                scheduleForm.scheduled_for = getDefaultScheduledFor();

                const firstCategory = categories.value.at(0);

                scheduleForm.category_id = firstCategory ? String(firstCategory.id) : '';
            },
        });
};

const page = usePage<{ flash?: { success?: string } }>();
const successMessage = computed(() => page.props.flash?.success ?? '');
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Agendamentos de Ofertas" />

        <div class="px-6 pb-10">
            <div class="mx-auto w-full max-w-5xl space-y-10">
                <section class="rounded-lg border border-border bg-card p-6 shadow-sm">
                    <header class="mb-6 flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-semibold tracking-tight text-foreground">
                                Agende uma nova oferta
                            </h1>
                            <p class="mt-1 text-sm text-muted-foreground">
                                Escolha uma data e hora para publicar automaticamente a oferta.
                            </p>
                        </div>
                    </header>

                    <form class="space-y-6" @submit.prevent="submit">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="category">Categoria</Label>
                                <select
                                    id="category"
                                    v-model="scheduleForm.category_id"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                >
                                    <option disabled value="">Selecione uma categoria</option>
                                    <option
                                        v-for="category in categories"
                                        :key="category.id"
                                        :value="String(category.id)"
                                    >
                                        {{ category.name }}
                                    </option>
                                </select>
                                <InputError :message="scheduleForm.errors.category_id" />
                            </div>

                            <div class="space-y-2">
                                <Label for="status">Status</Label>
                                <select
                                    id="status"
                                    v-model="scheduleForm.status"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                >
                                    <option
                                        v-for="status in statusOptions"
                                        :key="status.value"
                                        :value="status.value"
                                    >
                                        {{ status.label }}
                                    </option>
                                </select>
                                <InputError :message="scheduleForm.errors.status" />
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="title">Título</Label>
                                <Input id="title" v-model="scheduleForm.title" placeholder="Título da oferta" />
                                <InputError :message="scheduleForm.errors.title" />
                            </div>

                            <div class="space-y-2">
                                <Label for="price">Preço</Label>
                                <Input
                                    id="price"
                                    v-model="scheduleForm.price"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    placeholder="0,00"
                                />
                                <InputError :message="scheduleForm.errors.price" />
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="currency">Moeda</Label>
                                <select
                                    id="currency"
                                    v-model="scheduleForm.currency"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                >
                                    <option
                                        v-for="currency in currencyOptions"
                                        :key="currency.value"
                                        :value="currency.value"
                                    >
                                        {{ currency.label }}
                                    </option>
                                </select>
                                <InputError :message="scheduleForm.errors.currency" />
                            </div>

                            <div class="space-y-2">
                                <Label for="scheduled_for">Data e hora de publicação</Label>
                                <Input
                                    id="scheduled_for"
                                    v-model="scheduleForm.scheduled_for"
                                    type="datetime-local"
                                />
                                <InputError :message="scheduleForm.errors.scheduled_for" />
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="start_date">Início da oferta</Label>
                                <Input id="start_date" v-model="scheduleForm.start_date" type="datetime-local" />
                                <InputError :message="scheduleForm.errors.start_date" />
                            </div>

                            <div class="space-y-2">
                                <Label for="end_date">Término (opcional)</Label>
                                <Input id="end_date" v-model="scheduleForm.end_date" type="datetime-local" />
                                <InputError :message="scheduleForm.errors.end_date" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="description">Descrição</Label>
                            <textarea
                                id="description"
                                v-model="scheduleForm.description"
                                rows="4"
                                class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                placeholder="Descreva a oferta com detalhes"
                            />
                            <InputError :message="scheduleForm.errors.description" />
                        </div>

                        <div class="flex justify-end">
                            <Button :disabled="scheduleForm.processing" type="submit">
                                Agendar oferta
                            </Button>
                        </div>

                        <div v-if="successMessage" class="rounded-md bg-emerald-50 p-3 text-sm text-emerald-700">
                            {{ successMessage }}
                        </div>
                    </form>
                </section>

                <section class="rounded-lg border border-border bg-card p-6 shadow-sm">
                    <header class="mb-4">
                        <h2 class="text-xl font-semibold text-foreground">Próximas ofertas agendadas</h2>
                        <p class="mt-1 text-sm text-muted-foreground">
                            As ofertas abaixo serão publicadas automaticamente quando chegar a data programada.
                        </p>
                    </header>

                    <div v-if="scheduledOffers.length === 0" class="rounded-md border border-dashed p-6 text-center text-sm text-muted-foreground">
                        Nenhuma oferta agendada até o momento.
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-border">
                            <caption class="py-3 text-left text-sm text-muted-foreground">
                                Últimos agendamentos registrados.
                            </caption>
                            <thead class="bg-muted/40">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground">
                                        Oferta
                                    </th>
                                    <th scope="col" class="hidden px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground sm:table-cell">
                                        Categoria
                                    </th>
                                    <th scope="col" class="hidden px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground sm:table-cell">
                                        Status
                                    </th>
                                    <th scope="col" class="hidden px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground md:table-cell">
                                        Publicação
                                    </th>
                                    <th scope="col" class="px-4 py-2 text-right text-xs font-medium uppercase tracking-wider text-muted-foreground">
                                        Preço
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border bg-background">
                                <tr v-for="offer in scheduledOffers" :key="offer.id">
                                    <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-foreground">
                                        {{ offer.title }}
                                    </td>
                                    <td class="hidden whitespace-nowrap px-4 py-3 text-sm text-muted-foreground sm:table-cell">
                                        {{ categories.find((category) => category.id === offer.category_id)?.name ?? '—' }}
                                    </td>
                                    <td class="hidden whitespace-nowrap px-4 py-3 text-sm capitalize text-muted-foreground sm:table-cell">
                                        {{ offer.status === 'draft' ? 'Rascunho' : offer.status === 'active' ? 'Ativa' : 'Expirada' }}
                                    </td>
                                    <td class="hidden whitespace-nowrap px-4 py-3 text-sm text-muted-foreground md:table-cell">
                                        {{ offer.scheduled_for ? new Date(offer.scheduled_for).toLocaleString() : '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-medium text-foreground">
                                        {{
                                            new Intl.NumberFormat('pt-BR', {
                                                style: 'currency',
                                                currency: offer.currency,
                                            }).format(offer.price)
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </AppLayout>
</template>
