import React, { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { toast } from "sonner";
import { Plus, Edit, Trash2, Eye, MessageSquare, Star, Reply } from "lucide-react";

interface Feedback {
  id: number;
  groupId: number;
  name: string;
  email: string;
  subject: string;
  message: string;
  rating: number;
  status: string;
  response: string;
  createdAt: string;
  updatedAt: string;
}

export default function CorporateFeedback() {
  const [isResponseDialogOpen, setIsResponseDialogOpen] = useState(false);
  const [selectedFeedback, setSelectedFeedback] = useState<Feedback | null>(null);
  const [responseText, setResponseText] = useState("");

  const queryClient = useQueryClient();

  // Fetch feedback entries
  const { data: feedbacks = [], isLoading } = useQuery({
    queryKey: ['/api/corporate/feedback'],
    queryFn: async () => {
      const response = await fetch('/api/corporate/feedback', {
        credentials: 'include'
      });
      if (!response.ok) throw new Error('Failed to fetch feedback');
      return response.json();
    }
  });

  // Update feedback status mutation
  const updateStatusMutation = useMutation({
    mutationFn: async ({ id, status }: { id: number; status: string }) => {
      const response = await fetch(`/api/corporate/feedback/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ status })
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to update status');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/feedback'] });
      toast.success("Status updated successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  // Add response mutation
  const addResponseMutation = useMutation({
    mutationFn: async ({ id, response }: { id: number; response: string }) => {
      const res = await fetch(`/api/corporate/feedback/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ response, status: 'responded' })
      });

      if (!res.ok) {
        const error = await res.json();
        throw new Error(error.error || 'Failed to add response');
      }

      return res.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/feedback'] });
      setIsResponseDialogOpen(false);
      setResponseText("");
      setSelectedFeedback(null);
      toast.success("Response added successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  // Delete feedback mutation
  const deleteMutation = useMutation({
    mutationFn: async (id: number) => {
      const response = await fetch(`/api/corporate/feedback/${id}`, {
        method: 'DELETE',
        credentials: 'include'
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to delete feedback');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/feedback'] });
      toast.success("Feedback deleted successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  const handleResponse = (feedback: Feedback) => {
    setSelectedFeedback(feedback);
    setResponseText(feedback.response || "");
    setIsResponseDialogOpen(true);
  };

  const handleSubmitResponse = (e: React.FormEvent) => {
    e.preventDefault();
    if (selectedFeedback && responseText.trim()) {
      addResponseMutation.mutate({ id: selectedFeedback.id, response: responseText });
    }
  };

  const getStatusBadge = (status: string) => {
    switch (status) {
      case 'new':
        return <Badge variant="default">New</Badge>;
      case 'in_progress':
        return <Badge variant="secondary">In Progress</Badge>;
      case 'responded':
        return <Badge variant="outline">Responded</Badge>;
      case 'closed':
        return <Badge variant="destructive">Closed</Badge>;
      default:
        return <Badge variant="secondary">{status}</Badge>;
    }
  };

  const getRatingStars = (rating: number) => {
    return Array.from({ length: 5 }, (_, i) => (
      <Star
        key={i}
        className={`h-4 w-4 ${i < rating ? 'text-yellow-400 fill-current' : 'text-gray-300'}`}
      />
    ));
  };

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/corporate' },
    { icon: 'bi-building-gear', label: 'Head Office Login', path: '/dashboard/corporate/head-office-login' },
    { icon: 'bi-megaphone', label: 'Header Ads', path: '/dashboard/corporate/header-ads' },
    { icon: 'bi-window-stack', label: 'Popup Ads', path: '/dashboard/corporate/popup-ads' },
    { icon: 'bi-file-text', label: 'Terms & Conditions', path: '/dashboard/corporate/terms-conditions' },
    { icon: 'bi-info-circle', label: 'About Us', path: '/dashboard/corporate/about-us' },
    { icon: 'bi-images', label: 'Gallery', path: '/dashboard/corporate/gallery' },
    { icon: 'bi-telephone', label: 'Contact Us', path: '/dashboard/corporate/contact-us' },
    { icon: 'bi-share', label: 'Social Links', path: '/dashboard/corporate/social-links' },
    { icon: 'bi-chat-dots', label: 'Feedback', path: '/dashboard/corporate/feedback', active: true },
    { icon: 'bi-file-earmark-text', label: 'Applications', path: '/dashboard/corporate/applications' },
    { icon: 'bi-person-gear', label: 'Profile', path: '/dashboard/corporate/profile' },
  ];

  return (
    <DashboardLayout menuItems={menuItems}>
      <div className="space-y-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-3xl font-bold">Feedback Management</h1>
            <p className="text-muted-foreground">Manage customer feedback and suggestions</p>
          </div>
        </div>

        {/* Statistics Cards */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          <Card>
            <CardContent className="p-4">
              <div className="flex items-center space-x-2">
                <MessageSquare className="h-8 w-8 text-blue-600" />
                <div>
                  <p className="text-sm text-muted-foreground">Total Feedback</p>
                  <p className="text-2xl font-bold">{feedbacks.length}</p>
                </div>
              </div>
            </CardContent>
          </Card>
          <Card>
            <CardContent className="p-4">
              <div className="flex items-center space-x-2">
                <Eye className="h-8 w-8 text-green-600" />
                <div>
                  <p className="text-sm text-muted-foreground">New</p>
                  <p className="text-2xl font-bold">{feedbacks.filter((f: Feedback) => f.status === 'new').length}</p>
                </div>
              </div>
            </CardContent>
          </Card>
          <Card>
            <CardContent className="p-4">
              <div className="flex items-center space-x-2">
                <Reply className="h-8 w-8 text-orange-600" />
                <div>
                  <p className="text-sm text-muted-foreground">Responded</p>
                  <p className="text-2xl font-bold">{feedbacks.filter((f: Feedback) => f.status === 'responded').length}</p>
                </div>
              </div>
            </CardContent>
          </Card>
          <Card>
            <CardContent className="p-4">
              <div className="flex items-center space-x-2">
                <Star className="h-8 w-8 text-yellow-600" />
                <div>
                  <p className="text-sm text-muted-foreground">Avg Rating</p>
                  <p className="text-2xl font-bold">
                    {feedbacks.length > 0 
                      ? (feedbacks.reduce((sum: number, f: Feedback) => sum + (f.rating || 0), 0) / feedbacks.length).toFixed(1)
                      : '0.0'
                    }
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <Card>
          <CardHeader>
            <CardTitle>Feedback Entries</CardTitle>
          </CardHeader>
          <CardContent>
            {isLoading ? (
              <div className="text-center py-4">Loading...</div>
            ) : (
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Name</TableHead>
                    <TableHead>Subject</TableHead>
                    <TableHead>Rating</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Date</TableHead>
                    <TableHead>Actions</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {feedbacks.map((feedback: Feedback) => (
                    <TableRow key={feedback.id}>
                      <TableCell>
                        <div>
                          <div className="font-medium">{feedback.name}</div>
                          <div className="text-sm text-muted-foreground">{feedback.email}</div>
                        </div>
                      </TableCell>
                      <TableCell>
                        <div>
                          <div className="font-medium">{feedback.subject}</div>
                          <div className="text-sm text-muted-foreground max-w-xs truncate">
                            {feedback.message}
                          </div>
                        </div>
                      </TableCell>
                      <TableCell>
                        <div className="flex items-center space-x-1">
                          {getRatingStars(feedback.rating || 0)}
                          <span className="text-sm ml-2">({feedback.rating || 0})</span>
                        </div>
                      </TableCell>
                      <TableCell>{getStatusBadge(feedback.status)}</TableCell>
                      <TableCell>{new Date(feedback.createdAt).toLocaleDateString()}</TableCell>
                      <TableCell>
                        <div className="flex space-x-2">
                          <Button
                            size="sm"
                            variant="outline"
                            onClick={() => handleResponse(feedback)}
                          >
                            <Reply className="h-4 w-4" />
                          </Button>
                          <Button
                            size="sm"
                            variant="secondary"
                            onClick={() => updateStatusMutation.mutate({ 
                              id: feedback.id, 
                              status: feedback.status === 'new' ? 'in_progress' : 'closed' 
                            })}
                          >
                            <Eye className="h-4 w-4" />
                          </Button>
                          <Button
                            size="sm"
                            variant="destructive"
                            onClick={() => deleteMutation.mutate(feedback.id)}
                          >
                            <Trash2 className="h-4 w-4" />
                          </Button>
                        </div>
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            )}
          </CardContent>
        </Card>

        {/* Response Dialog */}
        <Dialog open={isResponseDialogOpen} onOpenChange={setIsResponseDialogOpen}>
          <DialogContent className="max-w-2xl">
            <DialogHeader>
              <DialogTitle>Respond to Feedback</DialogTitle>
            </DialogHeader>
            {selectedFeedback && (
              <div className="space-y-4">
                <div className="bg-gray-50 p-4 rounded-lg">
                  <h4 className="font-medium">{selectedFeedback.subject}</h4>
                  <p className="text-sm text-muted-foreground mb-2">
                    From: {selectedFeedback.name} ({selectedFeedback.email})
                  </p>
                  <p className="text-sm">{selectedFeedback.message}</p>
                  <div className="flex items-center space-x-1 mt-2">
                    <span className="text-sm">Rating:</span>
                    {getRatingStars(selectedFeedback.rating || 0)}
                  </div>
                </div>
                
                <form onSubmit={handleSubmitResponse} className="space-y-4">
                  <div>
                    <Label htmlFor="response">Your Response</Label>
                    <Textarea
                      id="response"
                      value={responseText}
                      onChange={(e) => setResponseText(e.target.value)}
                      rows={6}
                      placeholder="Type your response here..."
                      required
                    />
                  </div>
                  <div className="flex justify-end space-x-2">
                    <Button type="button" variant="outline" onClick={() => setIsResponseDialogOpen(false)}>
                      Cancel
                    </Button>
                    <Button type="submit" disabled={addResponseMutation.isPending}>
                      {addResponseMutation.isPending ? "Sending..." : "Send Response"}
                    </Button>
                  </div>
                </form>
              </div>
            )}
          </DialogContent>
        </Dialog>
      </div>
    </DashboardLayout>
  );
}
