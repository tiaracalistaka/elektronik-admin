<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Pencil, Trash2, Plus, Package } from '@lucide/vue';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
    DialogClose,
} from '@/components/ui/dialog';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Produk', href: '#' },
        ],
    },
});

const page = usePage<any>();
const flash = computed(() => page.props.flash as { success?: string; error?: string });

const produks = computed(() => page.props.produks as any[]);
const kategoris = computed(() => page.props.kategoris as any[]);

const showAddDialog = ref(false);
const showEditDialog = ref(false);
const showDeleteDialog = ref(false);
const selectedProduk = ref<any>(null);

const form = ref({
    nama: '',
    kategori_id: '',
    harga: '',
    thumbnail: '',
    link_gambar: '',
    deskripsi: '',
    stok: 0,
});

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
};

const openAdd = () => {
    form.value = { nama: '', kategori_id: '', harga: '', thumbnail: '', link_gambar: '', deskripsi: '', stok: 0 };
    showAddDialog.value = true;
};

const openEdit = (produk: any) => {
    selectedProduk.value = produk;
    form.value = {
        nama: produk.nama,
        kategori_id: String(produk.kategori_id),
        harga: String(produk.harga),
        thumbnail: produk.thumbnail || '',
        link_gambar: produk.link_gambar || '',
        deskripsi: produk.deskripsi || '',
        stok: produk.stok || 0,
    };
    showEditDialog.value = true;
};

const openDelete = (produk: any) => {
    selectedProduk.value = produk;
    showDeleteDialog.value = true;
};

const submitAdd = () => {
    const formData = new FormData();
    formData.append('nama', form.value.nama);
    formData.append('kategori_id', form.value.kategori_id);
    formData.append('harga', form.value.harga);
    if (form.value.thumbnail) formData.append('thumbnail', form.value.thumbnail);
    if (form.value.link_gambar) formData.append('link_gambar', form.value.link_gambar);
    if (form.value.deskripsi) formData.append('deskripsi', form.value.deskripsi);
    formData.append('stok', String(form.value.stok));

    window.Inertia.post('/produk', Object.fromEntries(formData), {
        onSuccess: () => { showAddDialog.value = false; },
    });
};

const submitEdit = () => {
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('nama', form.value.nama);
    formData.append('kategori_id', form.value.kategori_id);
    formData.append('harga', form.value.harga);
    if (form.value.thumbnail) formData.append('thumbnail', form.value.thumbnail);
    if (form.value.link_gambar) formData.append('link_gambar', form.value.link_gambar);
    if (form.value.deskripsi) formData.append('deskripsi', form.value.deskripsi);
    formData.append('stok', String(form.value.stok));

    window.Inertia.post(`/produk/${selectedProduk.value.id}`, Object.fromEntries(formData), {
        onSuccess: () => { showEditDialog.value = false; },
    });
};

const submitDelete = () => {
    window.Inertia.post(`/produk/${selectedProduk.value.id}`, { _method: 'DELETE' }, {
        onSuccess: () => { showDeleteDialog.value = false; },
    });
};
</script>

<template>
    <Head title="Produk" />

    <!-- Flash Toast -->
    <div v-if="flash?.success" class="mx-6 mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
        {{ flash.success }}
    </div>
    <div v-if="flash?.error" class="mx-6 mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
        {{ flash.error }}
    </div>

    <div class="flex flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-foreground">Manajemen Produk</h1>
                <p class="text-sm text-muted-foreground">Kelola daftar produk elektronik</p>
            </div>
            <Button @click="openAdd" class="gap-2">
                <Plus class="h-4 w-4" />
                Tambah Produk
            </Button>
        </div>

        <!-- Table -->
        <div class="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border bg-muted/50">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">Thumbnail</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">Nama Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">Kategori</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">Harga</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">Stok</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-muted-foreground">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <tr v-for="(produk, i) in produks" :key="produk.id" class="hover:bg-muted/30 transition-colors">
                            <td class="px-4 py-3 text-sm text-muted-foreground">{{ i + 1 }}</td>
                            <td class="px-4 py-3">
                                <img
                                    v-if="produk.thumbnail"
                                    :src="produk.thumbnail"
                                    :alt="produk.nama"
                                    class="h-12 w-12 rounded-lg object-cover border border-border"
                                    @error="($event.target as HTMLImageElement).style.display='none'"
                                />
                                <div v-else class="flex h-12 w-12 items-center justify-center rounded-lg border border-border bg-muted">
                                    <Package class="h-5 w-5 text-muted-foreground" />
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="text-sm font-medium text-foreground">{{ produk.nama }}</p>
                                    <p v-if="produk.deskripsi" class="text-xs text-muted-foreground truncate max-w-[200px]">{{ produk.deskripsi }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ produk.kategori?.nama || '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold text-foreground">{{ formatCurrency(produk.harga) }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                    :class="produk.stok > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'"
                                >
                                    {{ produk.stok }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <Button variant="ghost" size="sm" @click="openEdit(produk)" class="h-8 w-8 p-0">
                                        <Pencil class="h-4 w-4 text-muted-foreground" />
                                    </Button>
                                    <Button variant="ghost" size="sm" @click="openDelete(produk)" class="h-8 w-8 p-0 hover:text-red-500">
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="produks.length === 0">
                            <td colspan="7" class="px-4 py-12 text-center text-sm text-muted-foreground">
                                Belum ada produk. Tambahkan produk pertama Anda.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Dialog -->
    <Dialog v-model:open="showAddDialog">
        <DialogContent class="max-w-md">
            <DialogHeader>
                <DialogTitle>Tambah Produk Baru</DialogTitle>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <div class="grid gap-2">
                    <Label for="add-nama">Nama Produk</Label>
                    <Input id="add-nama" v-model="form.nama" placeholder="Contoh: Iphone 15 Pro" />
                </div>
                <div class="grid gap-2">
                    <Label for="add-kategori">Kategori</Label>
                    <Select v-model="form.kategori_id">
                        <SelectTrigger id="add-kategori">
                            <SelectValue placeholder="Pilih kategori" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="kat in kategoris" :key="kat.id" :value="String(kat.id)">{{ kat.nama }}</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="grid gap-2">
                    <Label for="add-harga">Harga (Rp)</Label>
                    <Input id="add-harga" type="number" v-model="form.harga" placeholder="12000000" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="add-stok">Stok</Label>
                        <Input id="add-stok" type="number" v-model="form.stok" min="0" />
                    </div>
                </div>
                <div class="grid gap-2">
                    <Label for="add-thumbnail">Link Thumbnail</Label>
                    <Input id="add-thumbnail" v-model="form.thumbnail" placeholder="https://..." />
                </div>
                <div class="grid gap-2">
                    <Label for="add-deskripsi">Deskripsi</Label>
                    <Input id="add-deskripsi" v-model="form.deskripsi" placeholder="Deskripsi produk (opsional)" />
                </div>
            </div>
            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="outline">Batal</Button>
                </DialogClose>
                <Button @click="submitAdd">Simpan</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

    <!-- Edit Dialog -->
    <Dialog v-model:open="showEditDialog">
        <DialogContent class="max-w-md">
            <DialogHeader>
                <DialogTitle>Edit Produk</DialogTitle>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <div class="grid gap-2">
                    <Label for="edit-nama">Nama Produk</Label>
                    <Input id="edit-nama" v-model="form.nama" />
                </div>
                <div class="grid gap-2">
                    <Label for="edit-kategori">Kategori</Label>
                    <Select v-model="form.kategori_id">
                        <SelectTrigger id="edit-kategori">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="kat in kategoris" :key="kat.id" :value="String(kat.id)">{{ kat.nama }}</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="grid gap-2">
                    <Label for="edit-harga">Harga (Rp)</Label>
                    <Input id="edit-harga" type="number" v-model="form.harga" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="edit-stok">Stok</Label>
                        <Input id="edit-stok" type="number" v-model="form.stok" min="0" />
                    </div>
                </div>
                <div class="grid gap-2">
                    <Label for="edit-thumbnail">Link Thumbnail</Label>
                    <Input id="edit-thumbnail" v-model="form.thumbnail" />
                </div>
                <div class="grid gap-2">
                    <Label for="edit-deskripsi">Deskripsi</Label>
                    <Input id="edit-deskripsi" v-model="form.deskripsi" />
                </div>
            </div>
            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="outline">Batal</Button>
                </DialogClose>
                <Button @click="submitEdit">Perbarui</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

    <!-- Delete Dialog -->
    <Dialog v-model:open="showDeleteDialog">
        <DialogContent class="max-w-sm">
            <DialogHeader>
                <DialogTitle>Hapus Produk?</DialogTitle>
            </DialogHeader>
            <div class="py-4">
                <p class="text-sm text-muted-foreground">
                    Anda yakin ingin menghapus produk <strong>{{ selectedProduk?.nama }}</strong>? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="outline">Batal</Button>
                </DialogClose>
                <Button variant="destructive" @click="submitDelete">Hapus</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
