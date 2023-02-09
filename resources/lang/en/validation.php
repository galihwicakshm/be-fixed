<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'accepted_if' => 'The :attribute must be accepted when :other is :value.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute must only contain letters.',
    'alpha_dash' => 'The :attribute must only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute must only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'declined' => 'The :attribute must be declined.',
    'declined_if' => 'The :attribute must be declined when :other is :value.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal to :value.',
        'file' => 'The :attribute must be greater than or equal to :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal to :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal to :value.',
        'file' => 'The :attribute must be less than or equal to :value kilobytes.',
        'string' => 'The :attribute must be less than or equal to :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute must not be greater than :max.',
        'file' => 'The :attribute must not be greater than :max kilobytes.',
        'string' => 'The :attribute must not be greater than :max characters.',
        'array' => 'The :attribute must not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute must be a valid URL.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'first_name' => [
            'required' => 'Nama depan tidak bolek kosong.',
            'string' => 'Nama depan harus berupa string.',
            'regex' => 'Format nama depan tidak valid.',
        ],
        'college' => [
            'required' => 'Instansi tidak bolek kosong.',
            'string' => 'Instansi harus berupa string.',
            'regex' => 'Format instansi tidak valid.',
        ],
        'email' => [
            'required' => 'Email tidak bolek kosong.',
            'string' => 'Email harus berupa string.',
            'email' => 'Alamat Email tidak valid.',
            'unique' => 'Alamat Email sudah dipakai.',
        ],
        'password' => [
            'required' => 'Kata sandi tidak bolek kosong.',
            'string' => 'Email harus berupa string.',
            'confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'min' => 'Kata sandi harus minimal :min karakter.',
        ],
        'password_confirmation' => [
            'required' => 'Konfirmasi kata sandi tidak bolek kosong.',
        ],
        'label' => [
            'required' => 'Nama kategori tidak bolek kosong.',
            'unique' => 'Kategori sudah ada.',
        ],
        'thumbnail' => [
            'file' => 'Gambar mini harus berupa file.',
            'mimes' => 'Gambar mini harus berupa file dengan tipe: :values.',
        ],
        'title' => [
            'required' => 'Judul tidak bolek kosong.',
        ],
        'category' => [
            'required' => 'Kategori tidak bolek kosong.',
        ],
        'body' => [
            'required' => 'Konten tidak bolek kosong.',
        ],
        'name' => [
            'required' => 'Nama tidak bolek kosong.',
        ],
        'start_stock' => [
            'required' => 'Stok awal tidak bolek kosong.',
        ],
        'mass_unit' => [
            'required' => 'Satuan tidak bolek kosong.',
        ],
        'phone_number' => [
            'required' => 'Nomor handphone tidak bolek kosong.',
            'numeric' => 'Nomor handphone harus berupa angka.',
            'unique' => 'Nomor handphone sudah dipakai.',
        ],
        'research_field' => [
            'required' => 'Bidang penelitian tidak bolek kosong.',
        ],
        'short_description' => [
            'required' => 'Deskripsi singkat penelitian tidak bolek kosong.',
        ],
        'data_description' => [
            'required' => 'Deskripsi data tidak bolek kosong.',
        ],
        'shared_data' => [
            'required' => 'Menggunakan data bersama tidak bolek kosong.',
        ],
        'activity_plan' => [
            'required' => 'Rencana kegiatan tidak bolek kosong.',
        ],
        'output_plan' => [
            'required' => 'Rencana output penelitian tidak bolek kosong.',
        ],
        'facility_needs' => [
            'required' => 'Kebutuhan fasilitas tidak bolek kosong.',
        ],
        'docker_image' => [
            'required' => 'Docker image tidak bolek kosong.',
        ],
        'proposal_file' => [
            'required' => 'Unggah proposal tidak bolek kosong.',
            'file' => 'Unggah proposal harus berupa file.',
            'mimes' => 'Unggah proposal harus berupa file dengan tipe: :values.',
        ],
        'application_file' => [
            'required' => 'Unggah surat pengajuan penggunaan DGX tidak bolek kosong.',
            'file' => 'Unggah surat pengajuan penggunaan DGX harus berupa file.',
            'mimes' => 'Unggah surat pengajuan penggunaan DGX harus berupa file dengan tipe: :values.',
        ],
        'educational_level' => [
            'required' => 'Jenjang pendidikan tidak bolek kosong.',
        ],
        'study_program' => [
            'required' => 'Program studi tidak bolek kosong.',
        ],
        'gpu' => [
            'required' => 'Jumlah GPU tidak bolek kosong.',
            'numeric' => 'Jumlah GPU harus berupa angka.',
            'gt' => 'Jumlah GPU harus lebih besar dari :value.',
        ],
        'ram' => [
            'required' => 'Jumlah RAM tidak bolek kosong.',
            'numeric' => 'Jumlah RAM harus berupa angka.',
            'gt' => 'Jumlah RAM harus lebih besar dari :value.',
        ],
        'storage' => [
            'required' => 'Jumlah storage tidak bolek kosong.',
            'numeric' => 'Jumlah storage harus berupa angka.',
            'gt' => 'Jumlah storage harus lebih besar dari :value.',
        ],
        'partner' => [
            'required' => 'Nama partner / mahasiswa tidak bolek kosong.',
        ],
        'duration' => [
            'required' => 'Durasi tidak bolek kosong.',
            'numeric' => 'Durasi harus berupa angka.',
            'gt' => 'Durasi harus lebih besar dari :value.',
        ],
        'research_fee' => [
            'required' => 'Biaya penelitian tidak bolek kosong.',
            'numeric' => 'Biaya penelitian harus berupa angka.',
            'gt' => 'Biaya penelitian harus lebih besar dari :value.',
        ],
        'type' => [
            'required' => 'Jenis tidak bolek kosong.',
        ],
        'document_type' => [
            'required' => 'Jenis dokumen tidak bolek kosong.',
        ],
        'file' => [
            'required' => 'File tidak bolek kosong.',
            'file' => 'Upload File harus berupa file.',
        ],
        'avatar' => [
            'required' => 'Avatar tidak bolek kosong.',
            'file' => 'Avatar harus berupa file.',
        ],
        'role' => [
            'required' => 'Role tidak bolek kosong.',
            'numeric' => 'Format role salah.',
        ],
        'subject' => [
            'required' => 'Subjek tidak bolek kosong.',
        ],
        'announcement' => [
            'required' => 'Pengumuman tidak bolek kosong.',
        ],
        'rev_description' => [
            'required' => 'Detail revisi tidak bolek kosong.',
        ],
        'appr_description' => [
            'required' => 'Detail disetujui tidak bolek kosong.',
        ],
        'type_of_proposal' => [
            'required' => 'Jenis penelitian tidak bolek kosong.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
