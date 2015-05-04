<tr>
    <td>{{ HTML::link(Storage::url($filePath), $file->getClientOriginalName(), ['target'=>'_blank']) }}</td>
    <td>
        {{
            HTML::link('', 'Delete', array(
                'class'=>'btn btn-danger btn-xs delete-file-uploaded',
                'data-file-path'=>$filePath,
                'data-file-folder'=>$fileFolder,
                'data-file-delete-url'=>route('maintenance.assets.images.uploads.destroy')
            ))
        }}

        {{
            Form::hidden(
                'files[]',
                sprintf('%s|%s', $fileName, str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()))
            )
        }}
    </td>
</tr>