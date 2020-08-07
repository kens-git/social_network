<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use App\Album;
use App\File;
use App\FileComment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;

class AlbumController extends Controller {
    public function getAlbums($username = null) {
        if($username && $username != Auth::user()->username) {
            $user = User::where('username', $username)->first();
            if(!$user) {
                return view('errors.user_not_found')->with('username', $username);
            }
            $albums = Album::getAlbums($user->id)->reverse();
            $cover_file = File::where('id', $user->cover_photo_id)->first();
            $profile_file = File::where('id', $user->profile_photo_id)->first();
            return view('albums.index')->with(['user' => $user, 'albums' => $albums,
                'cover_file' => $cover_file, 'profile_file' => $profile_file]);
        }
        $albums = Album::getAlbums()->reverse();
        return view('albums.index')->with(['user' => Auth::user(), 'albums' => $albums]);
    }

    public function postAlbum(Request $request) {
        $request->validate([
            'name' => 'required|min:1',
            'files' => 'required',
        ],[
            'files.required' => 'At least one file must be added.'
        ]);
        $album = Album::create([
            'user_id' => Auth::user()->id,
            'name' => $request->input('name')
        ]);
        $dir_path = sprintf('/uploads/%d/%d', Auth::user()->id, $album->id);
        // check if directory exists instead of creating it every time, in case makeDirectory changes in the future
        Storage::disk('local')->makeDirectory($dir_path);
        $files = $request->file('files');
        foreach($files as $file) {
            $extension = $this->mime2ext($file->getMimeType());
            $file_handle = File::create([
                'album_id' => $album->id,
                'name' => $file->getClientOriginalName(),
                'extension' => $extension,
                'description' => '',
                'user_id' => Auth::user()->id
            ]);
            //echo $dir_path;
            Storage::putFileAs($dir_path, $file, sprintf('%d.%s', $file_handle->id, $extension));

            // if($this->is_image($file->getMimeType())) {
            //     $filenamewithextension = $file->getClientOriginalName();
            //     //$filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            //     $extension = $this->mime2ext($file->getMimeType());
            //     $filenametostore = $file_handle->id.'-thumbnail.'.$extension;
            //     // TODO: any reason to store this just to retrieve it and resize it?
            //     Storage::putFileAs($dir_path, $file, $filenametostore);
            //     $thumbnailpath = $dir_path.'/'.$filenametostore;
            //     $img = Image::make(storage_path().'/app/files/'.$thumbnailpath)->resize(300, 300, function($constraint) {
            //         $constraint->aspectRatio();
            //     })->encode();
            //     Storage::put($dir_path.'/'.$filenametostore, $img);
            // }
            if($this->is_video($file->getMimeType())) {
                $ffmpeg = FFMpeg::create();
                $video = $ffmpeg->open($file);
                $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(0));
                $filenametostore = $file_handle->id.'-preview.png';
                $thumbnailpath = $dir_path.'/'.$filenametostore;
                $full_path = storage_path().'/app/files'.$thumbnailpath;
                $frame->save($full_path);
            }
        }
        return redirect()->route('albums');
    }

    public function getAlbumsView($username, $album_id) {
        $album = Album::where('id', $album_id)->first();
        $user = User::where('username', $username)->first();
        if(!$user) {
            return view('errors.user_not_found')->with('username', $username);
        }
        if(!$album || $album->user_id != $user->id) {
            return view('errors.album_not_found')->with(['username' => $username, 'album_id' => $album_id]);
        }
        $files = File::getFiles($album->id);
        $album_path = sprintf('app/files/uploads/%d/%d', Auth::user()->id, $album->id);
        $cover_file = File::where('id', $user->cover_photo_id)->first();
        $profile_file = File::where('id', $user->profile_photo_id)->first();
        return view('albums.view')->with(['user' => $user, 'files' => $files, 'album_path' => $album_path,
            'cover_file' => $cover_file, 'profile_file' => $profile_file]);
    }

    public function getAlbumFilePreview($username, $album_id, $file_id) {
        $file = File::where('id', $file_id)->first();
        if(!$file) {
            // TODO: file not found error view
            return 'file not found in database';
        }
        // $path = storage_path('app/files/uploads/') . $username . '/' .
        //     $album_id . '/' . $file_id . '.' . $file->extension;
        $user = User::where('username', $username)->first();
        if(!$user) {
            // TODO:
            return 'should be user not found error';
        }
        $path = sprintf('uploads/%d/%d/%d.%s', $user->id, $album_id, $file_id, $file->extension);
        //dd($path);
        if(!Storage::exists($path)) {
            // TODO: show error message
            return 'file not found on disk';
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        if($this->is_image($type)) {
            $image = Image::make($file);
            $image->fit(299, 250);
            return $image->response();
        } else if($this->is_video($type)) {
            $preview_path = sprintf('uploads/%d/%d/%d-preview.png', $user->id, $album_id, $file_id);
            $image = Image::make(Storage::get($preview_path));
            $image->fit(299, 250);
            return $image->response();
        }

        // $headers = ['Content-Type' => Storage::mimeType($path)];
        // $response = Response::make($file, 200, $headers);
    
        // return $response;
        $image = Image::make(Storage::get('document_preview.png'));
        $image->fit(299, 250);
        return $image->response();
    }

    public function getAlbumFile($username, $album_id, $file_id) {
        $file = File::where('id', $file_id)->first();
        if(!$file) {
            // TODO: file not found error view
            return 'file not found in database';
        }
        // $path = storage_path('app/files/uploads/') . $username . '/' .
        //     $album_id . '/' . $file_id . '.' . $file->extension;
        $user = User::where('username', $username)->first();
        if(!$user) {
            // TODO:
            return 'should be user not found error';
        }
        $path = sprintf('uploads/%d/%d/%d.%s', $user->id, $album_id, $file_id, $file->extension);
        if(!Storage::exists($path)) {
            // TODO: show error message
            return 'file not found on disk';
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        if($this->is_image($type)) {
            $image = Image::make($file);
            $image = $image->widen(1200, function ($constraint) {
                $constraint->upsize();
            });
            $image = $image->heighten(1200, function ($constraint) {
                $constraint->upsize();
            });
            return $image->response();
        }

        $headers = ['Content-Type' => Storage::mimeType($path)];
        $response = Response::make($file, 200, $headers);
    
        return $response;
    }

    public function getAlbumFileView($username, $album_id, $file_id) {
        $user = User::where('username', $username)->first();
        if(!$user) {
            return 'should be user not found error';
        }
        $file = File::where('id', $file_id)->first();
        $comments = FileComment::where('file_id', $file_id)->orderBy('updated_at', 'desc')->get();
        $cover_file = File::where('id', $user->cover_photo_id)->first();
        $profile_file = File::where('id', $user->profile_photo_id)->first();
        return view('albums.file_view')->with([
            'user' => $user,
            'album_id' => $album_id,
            'file' => $file,
            'comments' => $comments,
            'cover_file' => $cover_file,
            'profile_file' => $profile_file
        ]);
    }

    public function postAlbumFileView(Request $request, $username, $album_id, $file_id) {
        $request->validate([
            'comment' => 'required|min:1'
        ]);
        $user = User::where('username', $username);
        if(!$user) {
            return 'user not found error';
        }
        $file = File::where(['id' => $file_id, 'album_id' => $album_id])->first();
        if(!$file) {
            return 'file not found/invalid file id/album id combo';
        }
        FileComment::create([
            'user_id' => Auth::user()->id,
            'file_id' => $file->id,
            'content' => $request->input('comment')
        ]);
        return redirect()->route('albums.file.view', [$username, $album_id, $file_id]);
    }

    public function getFullSizeAlbumFile(Request $request, $username, $album_id, $file_id) {
        $file = File::where('id', $file_id)->first();
        if(!$file) {
            // TODO: file not found error view
            return 'file not found in database';
        }
        // $path = storage_path('app/files/uploads/') . $username . '/' .
        //     $album_id . '/' . $file_id . '.' . $file->extension;
        $user = User::where('username', $username)->first();
        if(!$user) {
            // TODO:
            return 'should be user not found error';
        }
        $path = sprintf('uploads/%d/%d/%d.%s', $user->id, $album_id, $file_id, $file->extension);
        if(!Storage::exists($path)) {
            // TODO: show error message
            return 'file not found on disk';
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        $headers = ['Content-Type' => Storage::mimeType($path)];
        $response = Response::make($file, 200, $headers);
    
        return $response;
    }

    protected function is_image($mime) {
        $mime_map = [
            'image/bmp'                                                                 => 'bmp',
            'image/x-bmp'                                                               => 'bmp',
            'image/x-bitmap'                                                            => 'bmp',
            'image/x-xbitmap'                                                           => 'bmp',
            'image/x-win-bitmap'                                                        => 'bmp',
            'image/x-windows-bmp'                                                       => 'bmp',
            'image/ms-bmp'                                                              => 'bmp',
            'image/x-ms-bmp'                                                            => 'bmp',
            'image/cdr'                                                                 => 'cdr',
            'image/x-cdr'                                                               => 'cdr',
            'image/gif'                                                                 => 'gif',
            'image/x-icon'                                                              => 'ico',
            'image/x-ico'                                                               => 'ico',
            'image/vnd.microsoft.icon'                                                  => 'ico',
            'image/jp2'                                                                 => 'jp2',
            'image/jpx'                                                                 => 'jp2',
            'image/jpm'                                                                 => 'jp2',
            'image/jpeg'                                                                => 'jpeg',
            'image/pjpeg'                                                               => 'jpeg',
            'image/png'                                                                 => 'png',
            'image/x-png'                                                               => 'png',
            'image/vnd.adobe.photoshop'                                                 => 'psd',
            'image/svg+xml'                                                             => 'svg',
            'image/tiff'                                                                => 'tiff',
        ];

        return isset($mime_map[$mime]) === true;
    }

    protected function is_video($mime) {
        $mime_map = [
            'video/3gpp2'                                                               => '3g2',
            'video/3gp'                                                                 => '3gp',
            'video/3gpp'                                                                => '3gp',
            'video/x-msvideo'                                                           => 'avi',
            'video/msvideo'                                                             => 'avi',
            'video/avi'                                                                 => 'avi',
            'video/x-f4v'                                                               => 'f4v',
            'video/x-flv'                                                               => 'flv',
            'video/mj2'                                                                 => 'jp2',
            'video/quicktime'                                                           => 'mov',
            'video/x-sgi-movie'                                                         => 'movie',
            'video/mp4'                                                                 => 'mp4',
            'video/mpeg'                                                                => 'mpeg',
            'video/ogg'                                                                 => 'ogg',
            'video/vnd.rn-realvideo'                                                    => 'rv',
            'video/webm'                                                                => 'webm',
            'video/x-ms-wmv'                                                            => 'wmv',
            'video/x-ms-asf'                                                            => 'wmv',
        ];

        return isset($mime_map[$mime]) === true;
    }

    protected function mime2ext($mime) {
        $mime_map = [
            'video/3gpp2'                                                               => '3g2',
            'video/3gp'                                                                 => '3gp',
            'video/3gpp'                                                                => '3gp',
            'application/x-compressed'                                                  => '7zip',
            'audio/x-acc'                                                               => 'aac',
            'audio/ac3'                                                                 => 'ac3',
            'application/postscript'                                                    => 'ai',
            'audio/x-aiff'                                                              => 'aif',
            'audio/aiff'                                                                => 'aif',
            'audio/x-au'                                                                => 'au',
            'video/x-msvideo'                                                           => 'avi',
            'video/msvideo'                                                             => 'avi',
            'video/avi'                                                                 => 'avi',
            'application/x-troff-msvideo'                                               => 'avi',
            'application/macbinary'                                                     => 'bin',
            'application/mac-binary'                                                    => 'bin',
            'application/x-binary'                                                      => 'bin',
            'application/x-macbinary'                                                   => 'bin',
            'image/bmp'                                                                 => 'bmp',
            'image/x-bmp'                                                               => 'bmp',
            'image/x-bitmap'                                                            => 'bmp',
            'image/x-xbitmap'                                                           => 'bmp',
            'image/x-win-bitmap'                                                        => 'bmp',
            'image/x-windows-bmp'                                                       => 'bmp',
            'image/ms-bmp'                                                              => 'bmp',
            'image/x-ms-bmp'                                                            => 'bmp',
            'application/bmp'                                                           => 'bmp',
            'application/x-bmp'                                                         => 'bmp',
            'application/x-win-bitmap'                                                  => 'bmp',
            'application/cdr'                                                           => 'cdr',
            'application/coreldraw'                                                     => 'cdr',
            'application/x-cdr'                                                         => 'cdr',
            'application/x-coreldraw'                                                   => 'cdr',
            'image/cdr'                                                                 => 'cdr',
            'image/x-cdr'                                                               => 'cdr',
            'zz-application/zz-winassoc-cdr'                                            => 'cdr',
            'application/mac-compactpro'                                                => 'cpt',
            'application/pkix-crl'                                                      => 'crl',
            'application/pkcs-crl'                                                      => 'crl',
            'application/x-x509-ca-cert'                                                => 'crt',
            'application/pkix-cert'                                                     => 'crt',
            'text/css'                                                                  => 'css',
            'text/x-comma-separated-values'                                             => 'csv',
            'text/comma-separated-values'                                               => 'csv',
            'application/vnd.msexcel'                                                   => 'csv',
            'application/x-director'                                                    => 'dcr',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
            'application/x-dvi'                                                         => 'dvi',
            'message/rfc822'                                                            => 'eml',
            'application/x-msdownload'                                                  => 'exe',
            'video/x-f4v'                                                               => 'f4v',
            'audio/x-flac'                                                              => 'flac',
            'video/x-flv'                                                               => 'flv',
            'image/gif'                                                                 => 'gif',
            'application/gpg-keys'                                                      => 'gpg',
            'application/x-gtar'                                                        => 'gtar',
            'application/x-gzip'                                                        => 'gzip',
            'application/mac-binhex40'                                                  => 'hqx',
            'application/mac-binhex'                                                    => 'hqx',
            'application/x-binhex40'                                                    => 'hqx',
            'application/x-mac-binhex40'                                                => 'hqx',
            'text/html'                                                                 => 'html',
            'image/x-icon'                                                              => 'ico',
            'image/x-ico'                                                               => 'ico',
            'image/vnd.microsoft.icon'                                                  => 'ico',
            'text/calendar'                                                             => 'ics',
            'application/java-archive'                                                  => 'jar',
            'application/x-java-application'                                            => 'jar',
            'application/x-jar'                                                         => 'jar',
            'image/jp2'                                                                 => 'jp2',
            'video/mj2'                                                                 => 'jp2',
            'image/jpx'                                                                 => 'jp2',
            'image/jpm'                                                                 => 'jp2',
            'image/jpeg'                                                                => 'jpeg',
            'image/pjpeg'                                                               => 'jpeg',
            'application/x-javascript'                                                  => 'js',
            'application/json'                                                          => 'json',
            'text/json'                                                                 => 'json',
            'application/vnd.google-earth.kml+xml'                                      => 'kml',
            'application/vnd.google-earth.kmz'                                          => 'kmz',
            'text/x-log'                                                                => 'log',
            'audio/x-m4a'                                                               => 'm4a',
            'application/vnd.mpegurl'                                                   => 'm4u',
            'audio/midi'                                                                => 'mid',
            'application/vnd.mif'                                                       => 'mif',
            'video/quicktime'                                                           => 'mov',
            'video/x-sgi-movie'                                                         => 'movie',
            'audio/mpeg'                                                                => 'mp3',
            'audio/mpg'                                                                 => 'mp3',
            'audio/mpeg3'                                                               => 'mp3',
            'audio/mp3'                                                                 => 'mp3',
            'video/mp4'                                                                 => 'mp4',
            'video/mpeg'                                                                => 'mpeg',
            'application/oda'                                                           => 'oda',
            'audio/ogg'                                                                 => 'ogg',
            'video/ogg'                                                                 => 'ogg',
            'application/ogg'                                                           => 'ogg',
            'application/x-pkcs10'                                                      => 'p10',
            'application/pkcs10'                                                        => 'p10',
            'application/x-pkcs12'                                                      => 'p12',
            'application/x-pkcs7-signature'                                             => 'p7a',
            'application/pkcs7-mime'                                                    => 'p7c',
            'application/x-pkcs7-mime'                                                  => 'p7c',
            'application/x-pkcs7-certreqresp'                                           => 'p7r',
            'application/pkcs7-signature'                                               => 'p7s',
            'application/pdf'                                                           => 'pdf',
            'application/octet-stream'                                                  => 'pdf',
            'application/x-x509-user-cert'                                              => 'pem',
            'application/x-pem-file'                                                    => 'pem',
            'application/pgp'                                                           => 'pgp',
            'application/x-httpd-php'                                                   => 'php',
            'application/php'                                                           => 'php',
            'application/x-php'                                                         => 'php',
            'text/php'                                                                  => 'php',
            'text/x-php'                                                                => 'php',
            'application/x-httpd-php-source'                                            => 'php',
            'image/png'                                                                 => 'png',
            'image/x-png'                                                               => 'png',
            'application/powerpoint'                                                    => 'ppt',
            'application/vnd.ms-powerpoint'                                             => 'ppt',
            'application/vnd.ms-office'                                                 => 'ppt',
            'application/msword'                                                        => 'doc',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'application/x-photoshop'                                                   => 'psd',
            'image/vnd.adobe.photoshop'                                                 => 'psd',
            'text/x-python'                                                             => 'py',
            'audio/x-realaudio'                                                         => 'ra',
            'audio/x-pn-realaudio'                                                      => 'ram',
            'application/x-rar'                                                         => 'rar',
            'application/rar'                                                           => 'rar',
            'application/x-rar-compressed'                                              => 'rar',
            'audio/x-pn-realaudio-plugin'                                               => 'rpm',
            'application/x-pkcs7'                                                       => 'rsa',
            'text/rtf'                                                                  => 'rtf',
            'text/richtext'                                                             => 'rtx',
            'video/vnd.rn-realvideo'                                                    => 'rv',
            'application/x-stuffit'                                                     => 'sit',
            'application/smil'                                                          => 'smil',
            'text/srt'                                                                  => 'srt',
            'image/svg+xml'                                                             => 'svg',
            'application/x-shockwave-flash'                                             => 'swf',
            'application/x-tar'                                                         => 'tar',
            'application/x-gzip-compressed'                                             => 'tgz',
            'image/tiff'                                                                => 'tiff',
            'text/plain'                                                                => 'txt',
            'text/x-vcard'                                                              => 'vcf',
            'application/videolan'                                                      => 'vlc',
            'text/vtt'                                                                  => 'vtt',
            'audio/x-wav'                                                               => 'wav',
            'audio/wave'                                                                => 'wav',
            'audio/wav'                                                                 => 'wav',
            'application/wbxml'                                                         => 'wbxml',
            'video/webm'                                                                => 'webm',
            'audio/x-ms-wma'                                                            => 'wma',
            'application/wmlc'                                                          => 'wmlc',
            'video/x-ms-wmv'                                                            => 'wmv',
            'video/x-ms-asf'                                                            => 'wmv',
            'application/xhtml+xml'                                                     => 'xhtml',
            'application/excel'                                                         => 'xl',
            'application/msexcel'                                                       => 'xls',
            'application/x-msexcel'                                                     => 'xls',
            'application/x-ms-excel'                                                    => 'xls',
            'application/x-excel'                                                       => 'xls',
            'application/x-dos_ms_excel'                                                => 'xls',
            'application/xls'                                                           => 'xls',
            'application/x-xls'                                                         => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
            'application/vnd.ms-excel'                                                  => 'xlsx',
            'application/xml'                                                           => 'xml',
            'text/xml'                                                                  => 'xml',
            'text/xsl'                                                                  => 'xsl',
            'application/xspf+xml'                                                      => 'xspf',
            'application/x-compress'                                                    => 'z',
            'application/x-zip'                                                         => 'zip',
            'application/zip'                                                           => 'zip',
            'application/x-zip-compressed'                                              => 'zip',
            'application/s-compressed'                                                  => 'zip',
            'multipart/x-zip'                                                           => 'zip',
            'text/x-scriptzsh'                                                          => 'zsh',
        ];

        return isset($mime_map[$mime]) === true ? $mime_map[$mime] : 'mime_type_not_found';
    }
}
