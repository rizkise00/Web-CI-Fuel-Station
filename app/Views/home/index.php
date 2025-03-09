<main class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex justify-between">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Fuel Station List</h1>
        <a href="<?= base_url('home/add-fuel-station'); ?>">
            <button class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition">
                Add Fuel Station
            </button>
        </a>
    </div>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <?php 
            $session = session();

            if ($session->getFlashdata('success')): ?>
                <div class="text-green-500 font-bold mb-4 text-center">
                    <?= esc($session->getFlashdata('success')) ?>
                </div>
            <?php endif; 
        ?>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Image
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Price
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Address
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fuel_station_list as $index => $station): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?= esc($index + 1) ?>
                        </th>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?= esc($station['name']) ?>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?php if ($station['image']): ?>
                                <img class="max-w-[150px] h-[80px] object-cover object-center" src="<?= base_url('uploads/images/' . $station['image']); ?>" alt="Image">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?= esc($station['fuel_type']) ?>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?= esc($station['fuel_price']) ?>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?= esc($station['address']) ?>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?= esc($station['status']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <a href="<?= base_url('home/edit-fuel-station/' . $station['id']); ?>" class="mr-1">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded">
                                    Edit
                                </button>
                            </a>
                            <button type="button" data-id="<?= $station['id']; ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-4 rounded deleteButton">
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-4">
            <?= $pager->simpleLinks('default', 'tailwind_pagination') ?>
        </div>
    </div>
</main>

<div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Are you sure?</h2>
            <p class="text-gray-600">Do you really want to delete this fuel station? This process cannot be undone.</p>
            <div class="mt-6 flex justify-end">
                <button id="cancelDelete" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                <button id="confirmDelete" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let deleteModal = document.getElementById('deleteModal');
        let confirmDelete = document.getElementById('confirmDelete');
        let cancelDelete = document.getElementById('cancelDelete');
        let deleteUrl = '';

        document.querySelectorAll('.deleteButton').forEach(button => {
            button.addEventListener('click', function () {
                let stationId = this.getAttribute('data-id');
                deleteUrl = "<?= base_url('home/delete-fuel-station/'); ?>" + stationId;

                deleteModal.classList.remove('hidden');
            });
        });

        cancelDelete.addEventListener('click', function () {
            deleteModal.classList.add('hidden');
        });

        confirmDelete.addEventListener('click', function () {
            window.location.href = deleteUrl;
        });
    });
</script>