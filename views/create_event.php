<?php 
include '../includes/header.php';
 ?>

<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-200">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Create New Event</h1>
        <p class="text-slate-500">Schedule a new program or service for the congregation.</p>
    </div>

    <form action="../api/save_event.php" method="POST" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Event Title</label>
                    <input type="text" name="title" required placeholder="e.g. Youth Mega Jam" 
                        class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Event Type</label>
                    <select name="event_type" class="w-full p-2.5 border border-slate-300 rounded-lg outline-none">
                        <option value="worship">Worship Service</option>
                        <option value="conference">Conference</option>
                        <option value="prayer">Prayer Meeting</option>
                        <option value="outreach">Outreach</option>
                        <option value="seminar">Seminar/Workshop</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
                        <input type="date" name="event_date" required 
                            class="w-full p-2.5 border border-slate-300 rounded-lg outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Time</label>
                        <input type="time" name="event_time" required 
                            class="w-full p-2.5 border border-slate-300 rounded-lg outline-none">
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Location</label>
                    <input type="text" name="location" placeholder="e.g. Main Sanctuary" 
                        class="w-full p-2.5 border border-slate-300 rounded-lg outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Expected Attendees</label>
                    <input type="number" name="expected_attendees" placeholder="0" 
                        class="w-full p-2.5 border border-slate-300 rounded-lg outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                    <textarea name="description" rows="3" placeholder="Brief details about the event..." 
                        class="w-full p-2.5 border border-slate-300 rounded-lg outline-none"></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
            <a href="events.php" class="px-6 py-2.5 text-slate-600 font-medium hover:bg-slate-50 rounded-lg transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                Save Event
            </button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>