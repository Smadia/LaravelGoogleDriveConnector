# Google Drive Laravel Connector

## Usage

### Get the list contents from your root directory
<code>
    GDL::ls()->toArray()
</code>

### Get the spesific directory
<code>
    GDL::dir('mydir')->ls()->toArray()
</code>

### Get the spesific index if you have two or directory with a same name
Index is starts from 0 (zero)<br>
<code>
    GDL::dir('mydir, 2)->ls()->toArray()
</code>

### Filter your directory scan
This filter is use the Laravel's collection methods
<pre>
GDL::dir(function($dir) {
    $dir->where('name', '=', 'mydir')
}, 1)->ls()->toArray()
</pre>

### Directory methods
Below is optional method to your directory
<table>
    <thead>
        <tr>
            <th>Number</th>
            <th>Method</th>
            <th>Usage</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td><code>ls()</code></td>
            <td>Get the list contents of the current directory</td>
            <td>This method will return the Smadia/GoogleDriveLaravel/ListContent Class</td>
        </tr>
        <tr>
            <td>2</td>
            <td><code>delete()</code></td>
            <td>Delete the current directory</td>
            <td>-</td>
        </tr>
        <tr>
            <td>3</td>
            <td><code>rename()</code></td>
            <td>Rename the current directory</td>
            <td>This method will return back the DirectoryHandler Class</td>
        </tr>
        <tr>
            <td>4</td>
            <td><code>put($filename, $contents = null)</code></td>
            <td>Put a new file to the current directory</td>
            <td>$content can be null if the $filename is instance of FileHandler class. This method will return the FileHandler class of the created file</td>
        </tr>
        <tr>
            <td>5</td>
            <td><code>file($filename, $extension, $index = 0)</code></td>
            <td>Get the specified file</td>
            <td>This method will return the FileHandler class</td>
        </tr>
        <tr>
            <td>6</td>
            <td><code>isExists()</code></td>
            <td>Check is the directory exists</td>
            <td>Return boolean type</td>
        </tr>
        <tr>
            <td>7</td>
            <td><code>mkdir($dirname)</code></td>
            <td>Make a new directory inside the current directory</td>
            <td>This method will return the DirectoryHandler class of the created directory</td>
        </tr>
        <tr>
            <td>8</td>
            <td><code>dir($dirname, $index = 0)</code></td>
            <td>Go to specified directory</td>
            <td>This method will return the DirectoryHandler class of the requested directory with certain index (default is 0)</td>
        </tr>
    </tbody>
</table>