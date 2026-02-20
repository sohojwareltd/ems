<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Enquiries Form Submission</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #f8f9ff 0%, #f5f8f2 50%, #ffffff 100%); margin: 0; padding: 0;">
    <div style="background-color: #ffffff; max-width: 600px; margin: 30px auto; padding: 30px; border-radius: 10px; border: 1px solid #dbe5d5; box-shadow: 0 12px 36px rgba(15, 23, 42, 0.14);">
        <h2 style="background: linear-gradient(135deg, #19390b, #0d1f06); color: #FAF9F7; padding: 15px 20px; border-radius: 8px; margin-top: 0; font-size: 22px;">
            📬 Enquiries Form Submission
        </h2>

        <div style="margin: 15px 0; font-size: 16px; color: #1f2937;"><strong style="display: inline-block; min-width: 150px; color: #19390b;">First Name:</strong> {{ $data['first_name'] }}</div>
        <div style="margin: 15px 0; font-size: 16px; color: #1f2937;"><strong style="display: inline-block; min-width: 150px; color: #19390b;">Last Name:</strong> {{ $data['last_name'] }}</div>
        <div style="margin: 15px 0; font-size: 16px; color: #1f2937;"><strong style="display: inline-block; min-width: 150px; color: #19390b;">Email:</strong> {{ $data['email'] }}</div>
        <div style="margin: 15px 0; font-size: 16px; color: #1f2937;"><strong style="display: inline-block; min-width: 150px; color: #19390b;">Phone:</strong>{{$data['phone_full'] ?? ($data['phone'] ?? 'N/A')}}</div>
        <div style="margin: 15px 0; font-size: 16px; color: #1f2937;"><strong style="display: inline-block; min-width: 150px; color: #19390b;">Category:</strong> {{ $data['contact_category_name'] ?? 'N/A' }}</div>
        {{-- <div style="margin: 15px 0; font-size: 16px; color: #333;"><strong style="display: inline-block; min-width: 150px; color: #222;">Subject:</strong> {{ $data['subject'] }}</div> --}}
        <div style="margin: 15px 0; font-size: 16px; color: #1f2937;"><strong style="display: inline-block; min-width: 150px; color: #19390b;">Message:</strong><br><span style="display: block; margin-left: 150px;">{!! nl2br(e($data['message'])) !!}</span></div>


        {{-- <div style="margin-top: 30px; font-size: 14px; color: #777; text-align: center;">
            This message was submitted via your website contact form.
        </div> --}}
    </div>
</body>
</html>
