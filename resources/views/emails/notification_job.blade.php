<!-- resources/views/emails/notification_job.blade.php -->

<p>A new job matching your search criteria has been posted:</p>

<p><strong>Job Name:</strong> {{ $job->name }}</p>
<p><strong>Salary:</strong> {{ $job->salary }}</p>
<p><strong>Country:</strong> {{ $job->country }}</p>

<p>Feel free to apply if you're interested!</p>